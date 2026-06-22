<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorController extends Controller
{

    public function index(Request $request)
    {
        $query = Vendor::with('user', 'plan', 'plan_price');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('state', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by subscription status
        if ($request->has('subscription_status') && !empty($request->subscription_status)) {
            $query->where('subscription_status', $request->subscription_status);
        }

        // Filter by verification
        if ($request->has('is_verified') && $request->is_verified !== '') {
            $query->where('is_verified', $request->is_verified);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $vendors = $query->paginate(20)->withQueryString();

        // Get statistics
        $stats = [
            'total' => Vendor::count(),
            'active' => Vendor::where('status', 'active')->count(),
            'pending' => Vendor::where('status', 'pending')->count(),
            'suspended' => Vendor::where('status', 'suspended')->count(),
            'rejected' => Vendor::where('status', 'rejected')->count(),
            'verified' => Vendor::where('is_verified', true)->count(),
            'unverified' => Vendor::where('is_verified', false)->count(),
            'trial' => Vendor::where('subscription_status', 'trial')->count(),
            'subscribed' => Vendor::where('subscription_status', 'active')->count(),
        ];

        // If API request, return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $vendors->items(),
                'current_page' => $vendors->currentPage(),
                'last_page' => $vendors->lastPage(),
                'per_page' => $vendors->perPage(),
                'total' => $vendors->total(),
                'from' => $vendors->firstItem(),
                'to' => $vendors->lastItem(),
                'stats' => $stats
            ]);
        }

        return response()->json(['vendors' => $vendors, 'stats' => $stats]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            // User fields
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',

            // Vendor fields
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'business_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'timezone' => 'nullable|string|max:100',

            // Address
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Settings
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:pending,active,suspended,rejected',
            'is_verified' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'vendor',
            ]);

            // Create vendor
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'slug' => Str::slug($validated['business_name']) . '-' . uniqid(),
                'status' => $validated['status'],
                'subscription_status' => 'trial',
                'stripe_customer_id' => null,
                'stripe_subscription_id' => null,
                'stripe_account_id' => null,
                'stripe_account_status' => 'pending',
                'plan_id' => null,
                'price_id' => null,
                'trial_ends_at' => now()->addDays(14),
                'subscription_starts_at' => null,
                'subscription_ends_at' => null,

                // Business Information
                'business_name' => $validated['business_name'],
                'description' => $validated['description'],
                'phone' => $validated['business_phone'] ?? $validated['phone'],
                'website' => $validated['website'] ?? null,
                'timezone' => $validated['timezone'] ?? 'UTC',

                // Address
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'country' => $validated['country'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,

                // Media
                'logo' => null,
                'cover_image' => null,

                // Verification & Status
                'is_verified' => $validated['is_verified'] ?? false,
                'verified_at' => $validated['is_verified'] ? now() : null,
                'commission_rate' => $validated['commission_rate'] ?? 10.00,
                'earnings' => 0,
                'rating' => 0,
                'total_reviews' => 0,

                // Settings
                'settings' => json_encode([
                    'notifications' => true,
                    'email_notifications' => true,
                    'auto_accept_bookings' => false,
                    'booking_buffer_time' => 15,
                    'max_bookings_per_day' => null,
                ]),
                'social_links' => json_encode([
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'linkedin' => null,
                    'youtube' => null,
                ]),
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor created successfully!',
                    'vendor' => $vendor->load('user')
                ], 201);
            }

            return redirect()
                ->route('admin.vendors.index')
                ->with('success', 'Vendor created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create vendor: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to create vendor: ' . $e->getMessage());
        }
    }


    public function show(Vendor $vendor)
    {
        $vendor->load('user', 'plan', 'plan_price');

        // Get statistics
        $stats = [
            'total_services' => $vendor->services()->count(),
            'total_appointments' => $vendor->appointments()->count(),
            'total_staff' => $vendor->staff()->count(),
            'total_earnings' => $vendor->earnings,
            'total_reviews' => $vendor->total_reviews,
            'average_rating' => $vendor->rating,
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'vendor' => $vendor,
                'stats' => $stats
            ]);
        }

        return response()->json(['vendor' => $vendor, 'stats' => $stats]);
    }


    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            // User fields
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $vendor->user_id,
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',

            // Vendor fields
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'business_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'timezone' => 'nullable|string|max:100',

            // Address
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Settings
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:pending,active,suspended,rejected',
            'is_verified' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $vendor->user->update($userData);

            // Update vendor
            $vendorData = [
                'business_name' => $validated['business_name'],
                'description' => $validated['description'],
                'phone' => $validated['business_phone'] ?? $validated['phone'],
                'website' => $validated['website'] ?? null,
                'timezone' => $validated['timezone'] ?? 'UTC',
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'country' => $validated['country'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'status' => $validated['status'],
                'commission_rate' => $validated['commission_rate'] ?? 10.00,
                'is_verified' => $validated['is_verified'] ?? false,
            ];

            // Update verified_at if verification status changed
            if ($validated['is_verified'] && !$vendor->is_verified) {
                $vendorData['verified_at'] = now();
            } elseif (!$validated['is_verified'] && $vendor->is_verified) {
                $vendorData['verified_at'] = null;
            }

            $vendor->update($vendorData);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor updated successfully!',
                    'vendor' => $vendor->fresh()->load('user')
                ]);
            }

            return redirect()
                ->route('admin.vendors.index')
                ->with('success', 'Vendor updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update vendor: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to update vendor: ' . $e->getMessage());
        }
    }


    public function destroy(Vendor $vendor)
    {
        DB::beginTransaction();
        try {
            $vendor->delete();

            $vendor->user->delete();

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vendor deleted successfully!'
                ]);
            }

            return redirect()
                ->route('admin.vendors.index')
                ->with('success', 'Vendor deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete vendor: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete vendor: ' . $e->getMessage());
        }
    }
}
