<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\ServiceCategory;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function categories()
    {
        $categories = ServiceCategory::with('services')->where('is_active', 1)->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function vendors(Request $request)
    {
        $query = Vendor::with('services')
            ->where('status', 'active')
            ->where(function ($q) {
                $q->where(function ($query) {
                    // Trial not ended
                    $query->where('subscription_status', 'trial')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('trial_ends_at')
                                ->orWhere('trial_ends_at', '>', now());
                        });
                })->orWhere(function ($query) {
                    // Active not ended
                    $query->where('subscription_status', 'active')
                        ->where(function ($subQuery) {
                            $subQuery->whereNull('subscription_ends_at')
                                ->orWhere('subscription_ends_at', '>', now());
                        });
                });
            });

        if ($request->filled('category')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        $vendors = $query->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('reviews_avg_rating', 'desc')
            ->paginate(20);

        return response()->json($vendors);
    }

    public function vendor($slug)
    {
        $vendor = Vendor::where('slug', $slug)
            ->firstOrFail();

        $vendor->load(['user', 'services.category', 'reviews.customer', 'staff']);
        $vendor->rating = $vendor->reviews()->avg('rating') ?? 0;

        return response()->json(['vendor' => $vendor, 'stripe_key' => config('services.stripe.key')]);
    }


    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            'zip' => 'nullable|string',
            'state' => 'nullable|string',
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->fresh()
        ]);
    }

    public function getServices(Vendor $vendor)
    {
        $services = $vendor->services()
            ->with('category')
            ->where('is_active', true)
            ->get();

        return response()->json($services);
    }

    public function getStaff(Vendor $vendor)
    {
        $staff = $vendor->staff()
            ->with('services')
            ->where('is_active', true)
            ->get();

        return response()->json($staff);
    }

    public function getSchedule(Vendor $vendor, Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $schedule = $vendor->schedules()
            ->where('day_of_week', strtolower(Carbon::parse($date)->format('l')))
            ->where('is_active', true)
            ->get();

        $holidays = $vendor->holidays()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();

        return response()->json([
            'schedule' => $schedule,
            'is_holiday' => $holidays,
        ]);
    }
}
