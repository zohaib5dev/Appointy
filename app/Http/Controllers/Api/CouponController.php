<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $query = Coupon::query();
            
            if ($request->vendor_id) {
                $query->where('vendor_id', $request->vendor_id);
            }

            $coupons = $query->paginate($request->per_page ?? 15);
            return $this->success($coupons, 'Coupons retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'vendor_id' => 'required|exists:vendors,id',
                'code' => 'required|string|unique:coupons',
                'type' => 'required|in:percentage,fixed',
                'value' => 'required|numeric|min:0',
                'min_spend' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:0',
                'expires_at' => 'nullable|date',
            ]);

            $coupon = Coupon::create($validated);
            return $this->success($coupon, 'Coupon created', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Coupon $coupon)
    {
        return $this->success($coupon, 'Coupon retrieved successfully');
    }

    public function update(Request $request, Coupon $coupon)
    {
        try {
            $validated = $request->validate([
                'code' => 'string|unique:coupons,code,' . $coupon->id,
                'type' => 'in:percentage,fixed',
                'value' => 'numeric|min:0',
                'min_spend' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:0',
                'expires_at' => 'nullable|date',
                'is_active' => 'boolean',
            ]);

            $coupon->update($validated);
            return $this->success($coupon, 'Coupon updated');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return $this->success(null, 'Coupon deleted');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
