<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with stats.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $customers = $query->paginate($request->get('per_page', 20))->withQueryString();

        // Get statistics
        $stats = [
            'total' => User::where('role', 'customer')->count(),
            'active' => User::where('role', 'customer')->where('status', 'active')->count(),
            'inactive' => User::where('role', 'customer')->where('status', 'inactive')->count(),
            'suspended' => User::where('role', 'customer')->where('status', 'suspended')->count(),
            'verified' => User::where('role', 'customer')->whereNotNull('email_verified_at')->count(),
            'unverified' => User::where('role', 'customer')->whereNull('email_verified_at')->count(),
        ];

        return response()->json([
            'data' => $customers->items(),
            'current_page' => $customers->currentPage(),
            'last_page' => $customers->lastPage(),
            'per_page' => $customers->perPage(),
            'total' => $customers->total(),
            'from' => $customers->firstItem(),
            'to' => $customers->lastItem(),
            'stats' => $stats
        ]);
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'customer',
                'status' => $validated['status'],
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zip' => $validated['zip'] ?? null,
                'country' => $validated['country'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'customer' => $user
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer'
            ], 404);
        }

        // Load appointments with relationships
        $customer->load(['appointments' => function ($query) {
            $query->with(['vendor', 'service'])->orderBy('created_at', 'desc')->limit(10);
        }]);

        // Get statistics
        $stats = [
            'total_appointments' => $customer->appointments()->count(),
            'total_spent' => $customer->appointments()->sum('total_amount'),
            'total_reviews' => $customer->reviews()->count(),
            'average_rating' => $customer->reviews()->avg('rating') ?? 0,
        ];

        return response()->json([
            'customer' => $customer,
            'stats' => $stats
        ]);
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'status' => $validated['status'],
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zip' => $validated['zip'] ?? null,
                'country' => $validated['country'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $customer->update($userData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully!',
                'customer' => $customer->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Delete customer (soft delete or hard delete)
            $customer->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update customer status.
     */
    public function updateStatus(Request $request, User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'User is not a customer'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $customer->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Customer status updated successfully!',
            'customer' => $customer
        ]);
    }

    /**
     * Get customer statistics.
     */
    public function stats()
    {
        $stats = [
            'total' => User::where('role', 'customer')->count(),
            'active' => User::where('role', 'customer')->where('status', 'active')->count(),
            'inactive' => User::where('role', 'customer')->where('status', 'inactive')->count(),
            'suspended' => User::where('role', 'customer')->where('status', 'suspended')->count(),
            'verified' => User::where('role', 'customer')->whereNotNull('email_verified_at')->count(),
            'unverified' => User::where('role', 'customer')->whereNull('email_verified_at')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk action for customers.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,suspend,delete'
        ]);

        $action = $request->action;
        $customerIds = $request->customer_ids;

        DB::beginTransaction();
        try {
            switch ($action) {
                case 'activate':
                    User::whereIn('id', $customerIds)
                        ->where('role', 'customer')
                        ->update(['status' => 'active']);
                    break;
                case 'suspend':
                    User::whereIn('id', $customerIds)
                        ->where('role', 'customer')
                        ->update(['status' => 'suspended']);
                    break;
                case 'delete':
                    User::whereIn('id', $customerIds)
                        ->where('role', 'customer')
                        ->delete();
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk action completed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action: ' . $e->getMessage()
            ], 500);
        }
    }
}