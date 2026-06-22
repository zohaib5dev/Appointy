<?php
// app/Http/Controllers/Admin/PlanController.php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('prices')->orderBy('created_at', 'desc')->get();
        return response()->json($plans);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:plans,name|max:255',
            'description' => 'nullable|string',
            'services_limit' => 'required|integer|min:-1',
            'staff_limit' => 'required|integer|min:-1',
            'appointments_limit' => 'required|integer|min:-1',
            'storage_limit' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'prices' => 'required|array|min:1',
            'prices.*.interval' => 'required|string|in:monthly,yearly,quarterly,one_time',
            'prices.*.price_id' => 'required|string|unique:plan_prices,price_id', // Changed to price_id
            'prices.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $plan = Plan::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'services_limit' => $validated['services_limit'],
                'staff_limit' => $validated['staff_limit'],
                'appointments_limit' => $validated['appointments_limit'],
                'storage_limit' => $validated['storage_limit'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            foreach ($validated['prices'] as $priceData) {
                $plan->prices()->create([
                    'interval' => $priceData['interval'],
                    'price_id' => $priceData['price_id'], // Changed to price_id
                    'price' => $priceData['price'],
                ]);
            }

            DB::commit();
            return response()->json($plan->load('prices'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create plan: ' . $e->getMessage()], 500);
        }
    }

    public function show(Plan $plan)
    {
        return response()->json($plan->load('prices'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('plans')->ignore($plan->id)],
            'description' => 'nullable|string',
            'services_limit' => 'required|integer|min:-1',
            'staff_limit' => 'required|integer|min:-1',
            'appointments_limit' => 'required|integer|min:-1',
            'storage_limit' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'prices' => 'required|array|min:1',
            'prices.*.id' => 'nullable|exists:plan_prices,id',
            'prices.*.interval' => 'required|string|in:monthly,yearly,quarterly,one_time',
            'prices.*.price_id' => 'required|string', // Changed to price_id
            'prices.*.price' => 'required|numeric|min:0',
        ]);

        // Handle unique validation for price_id manually
        foreach ($validated['prices'] as $priceData) {
            $query = PlanPrice::where('price_id', $priceData['price_id']); // Changed to price_id
            
            if (isset($priceData['id'])) {
                $query->where('id', '!=', $priceData['id']);
            }
            
            if ($query->exists()) {
                return response()->json([
                    'message' => "The price ID '{$priceData['price_id']}' has already been taken."
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            $plan->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'services_limit' => $validated['services_limit'],
                'staff_limit' => $validated['staff_limit'],
                'appointments_limit' => $validated['appointments_limit'],
                'storage_limit' => $validated['storage_limit'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            $existingPriceIds = $plan->prices->pluck('id')->toArray();
            $updatedPriceIds = [];

            foreach ($validated['prices'] as $priceData) {
                if (isset($priceData['id'])) {
                    $price = PlanPrice::find($priceData['id']);
                    if ($price) {
                        $price->update([
                            'interval' => $priceData['interval'],
                            'price_id' => $priceData['price_id'], // Changed to price_id
                            'price' => $priceData['price'],
                        ]);
                        $updatedPriceIds[] = $price->id;
                    }
                } else {
                    $price = $plan->prices()->create([
                        'interval' => $priceData['interval'],
                        'price_id' => $priceData['price_id'], // Changed to price_id
                        'price' => $priceData['price'],
                    ]);
                    $updatedPriceIds[] = $price->id;
                }
            }

            $pricesToDelete = array_diff($existingPriceIds, $updatedPriceIds);
            if (!empty($pricesToDelete)) {
                PlanPrice::whereIn('id', $pricesToDelete)->delete();
            }

            DB::commit();
            return response()->json($plan->load('prices'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update plan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Plan $plan)
    {
        DB::beginTransaction();
        try {
            $plan->prices()->delete();
            $plan->delete();
            DB::commit();
            return response()->json(['message' => 'Plan deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete plan'], 500);
        }
    }
}