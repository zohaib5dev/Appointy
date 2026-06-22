<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class DashboardController extends BaseApiController
{
    public function index()
    {
        $vendor = Auth::user()->vendor;

        // Get weekly appointments data
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weeklyAppointments = [];

        foreach ($days as $index => $day) {
            $date = now()->startOfWeek()->addDays($index);
            $count = $vendor->appointments()
                ->whereDate('start_time', $date)
                ->count();
            $weeklyAppointments[] = $count;
        }

        // Get monthly revenue data (last 6 months)
        $months = [];
        $monthlyRevenue = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');
            $revenue = (float) $vendor->appointments()
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');
            $monthlyRevenue[] = $revenue;
        }

        // Calculate completion rate
        $totalAppointments = $vendor->appointments()->count();
        $completedAppointments = $vendor->appointments()->where('status', 'completed')->count();
        $completionRate = $totalAppointments > 0
            ? round(($completedAppointments / $totalAppointments) * 100)
            : 0;

        $stats = [
            // Main stats cards
            'today_appointments' => $vendor->appointments()->whereDate('start_time', today())->count(),
            'total_revenue' => (float) $vendor->appointments()
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'this_month_revenue' => (float) $vendor->appointments()
                ->where('payment_status', 'paid')
                ->whereMonth('start_time', now()->month)
                ->whereYear('start_time', now()->year)
                ->sum('total_amount'),
            'total_customers' => $vendor->appointments()
                ->whereNotNull('customer_id')
                ->distinct('customer_id')
                ->count('customer_id'),
            'average_rating' => round($vendor->reviews()->avg('rating') ?? 0, 1),

            // Additional stats cards
            'total_appointments' => $totalAppointments,
            'upcoming_appointments' => $vendor->appointments()
                ->where('start_time', '>', now())
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->count(),
            'total_reviews' => $vendor->reviews()->count(),
            'completion_rate' => $completionRate,
            'completed_appointments' => $completedAppointments,

            // Chart data 
            'chart_data' => [
                'appointments' => [
                    'labels' => $days,
                    'data' => $weeklyAppointments
                ],
                'revenue' => [
                    'labels' => $months,
                    'data' => $monthlyRevenue
                ]
            ],

            // Recent appointments
            'recent_appointments' => $vendor->appointments()
                ->with(['customer', 'service'])
                ->latest('start_time')
                ->limit(10)
                ->get()
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'booking_number' => $appointment->booking_number,
                        'customer' => $appointment->customer ? [
                            'name' => $appointment->customer->name
                        ] : null,
                        'service' => $appointment->service ? [
                            'name' => $appointment->service->name
                        ] : null,
                        'start_time' => $appointment->start_time,
                        'status' => $appointment->status,
                        'total_amount' => $appointment->total_amount
                    ];
                }),
        ];

        return response()->json($stats);
    }


    public function checkStatus(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !$user->vendor) {
            return response()->json([
                'error' => 'Vendor not found'
            ], 404);
        }

        $vendor = $user->vendor;
        
        // Check subscription validity
        $subscriptionStatus = $this->checkSubscriptionValidity($vendor);
        
        return response()->json([
            'success' => true,
            'data' => [
                'subscription' => [
                    'status' => $vendor->subscription_status,
                    'is_valid' => $subscriptionStatus['is_valid'],
                    'message' => $subscriptionStatus['message'],
                    'trial_ends_at' => $vendor->trial_ends_at,
                    'subscription_ends_at' => $vendor->subscription_ends_at,
                    'remaining_days' => $subscriptionStatus['remaining_days'],
                    'is_expiring_soon' => $subscriptionStatus['is_expiring_soon'] ?? false,
                ],
                'plan' => $vendor->plan ? [
                    'id' => $vendor->plan->id,
                    'name' => $vendor->plan->name,
                ] : null,
            ]
        ]);
    }

   
    private function checkSubscriptionValidity($vendor)
    {
        $now = now();
        $remainingDays = null;
        $message = 'Subscription is active';
        $isValid = true;
        $isExpiringSoon = false;

        // Check vendor status first
        if ($vendor->status !== 'active') {
            return [
                'is_valid' => false,
                'message' => 'Your vendor account is not active',
                'remaining_days' => null,
                'is_expiring_soon' => false,
            ];
        }

        // Check subscription status
        if ($vendor->subscription_status === 'active') {
            if ($vendor->subscription_ends_at) {
                $endDate = \Carbon\Carbon::parse($vendor->subscription_ends_at);
                if ($now->greaterThan($endDate)) {
                    return [
                        'is_valid' => false,
                        'message' => 'Your subscription has expired',
                        'remaining_days' => null,
                        'is_expiring_soon' => false,
                    ];
                }
                $remainingDays = $now->diffInDays($endDate, false);
                $isExpiringSoon = $remainingDays <= 7 && $remainingDays >= 0;
                $message = "Subscription active - expires in {$remainingDays} days";
            } else {
                $message = 'Subscription is active (no end date)';
            }
        } 
        elseif ($vendor->subscription_status === 'trial') {
            if ($vendor->trial_ends_at) {
                $endDate = \Carbon\Carbon::parse($vendor->trial_ends_at);
                if ($now->greaterThan($endDate)) {
                    return [
                        'is_valid' => false,
                        'message' => 'Your trial has expired',
                        'remaining_days' => null,
                        'is_expiring_soon' => false,
                    ];
                }
                $remainingDays = $now->diffInDays($endDate, false);
                $isExpiringSoon = $remainingDays <= 3 && $remainingDays >= 0; // Trial expiring soon threshold
                $message = "Trial active - ends in {$remainingDays} days";
            } else {
                $message = 'Trial active (no end date)';
            }
        } 
        else {
            return [
                'is_valid' => false,
                'message' => 'Your subscription is ' . $vendor->subscription_status,
                'remaining_days' => null,
                'is_expiring_soon' => false,
            ];
        }

        return [
            'is_valid' => $isValid,
            'message' => $message,
            'remaining_days' => $remainingDays,
            'is_expiring_soon' => $isExpiringSoon,
        ];
    }
 
}
