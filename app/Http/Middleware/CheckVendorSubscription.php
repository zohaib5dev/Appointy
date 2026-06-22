<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user || !$user->vendor) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $vendor = $user->vendor;
        
        // Check if vendor is active
        if ($vendor->status !== 'active') {
            return response()->json([
                'error' => 'Your account is not active',
                'status' => $vendor->status
            ], 403);
        }

        // Check subscription status
        $isValidSubscription = $this->checkSubscriptionValidity($vendor);
        
        if (!$isValidSubscription) {
            return response()->json([
                'error' => 'Your subscription has expired or is inactive',
                'subscription_status' => $vendor->subscription_status,
                'trial_ends_at' => $vendor->trial_ends_at,
                'subscription_ends_at' => $vendor->subscription_ends_at
            ], 403);
        }

        // Check limits for specific actions
        $this->checkLimits($vendor, $request);

        return $next($request);
    }

    /**
     * Check if subscription is valid
     */
    private function checkSubscriptionValidity($vendor): bool
    {
        // If status is active
        if ($vendor->subscription_status === 'active') {
            // Check if subscription has an end date and it's not expired
            if ($vendor->subscription_ends_at) {
                return now()->lessThan($vendor->subscription_ends_at);
            }
            return true; // No end date set, assume active
        }

        // If status is trial
        if ($vendor->subscription_status === 'trial') {
            // Check if trial has an end date and it's not expired
            if ($vendor->trial_ends_at) {
                return now()->lessThan($vendor->trial_ends_at);
            }
            return true; // No end date set, assume active
        }

        // Other statuses (expired, cancelled, etc.)
        return false;
    }

    /**
     * Check and apply limits
     */
    private function checkLimits($vendor, Request $request): void
    {
        // Get vendor's plan
        $plan = \App\Models\Plan::where('id', $vendor->plan_id)->first();
        
        if (!$plan) {
            // If no plan, use default limits or allow basic functionality
            $this->applyDefaultLimits($vendor);
            return;
        }

        // Apply limits based on plan
        $this->applyPlanLimits($vendor, $plan);
    }

    private function applyDefaultLimits($vendor): void
    {
        // Set default limits for trial or basic users
        config(['vendor.limits.services' => 5]);
        config(['vendor.limits.staff' => 3]);
        config(['vendor.limits.appointments' => 50]);
    }

    private function applyPlanLimits($vendor, $plan): void
    {
        // Store limits in config for use in controllers
        config(['vendor.limits.services' => $plan->services_limit]);
        config(['vendor.limits.staff' => $plan->staff_limit]);
        config(['vendor.limits.appointments' => $plan->appointments_limit]);
        config(['vendor.limits.storage' => $plan->storage_limit]);
    }
}