<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseApiController
{
    /**
     * Get vendor's report data
     */
    public function index(Request $request)
    {
        // Get the authenticated vendor ID
        $vendorId = auth()->user()->vendor->id;
        
        if (!$vendorId) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        // Set default date range (last 30 days if not specified)
        $fromDate = $request->get('from_date') 
            ? now()->parse($request->from_date)->startOfDay() 
            : now()->subDays(30)->startOfDay();
        
        $toDate = $request->get('to_date') 
            ? now()->parse($request->to_date)->endOfDay() 
            : now()->endOfDay();

        // Base queries for this vendor only
        $appointmentsQuery = Appointment::where('vendor_id', $vendorId)
            ->whereBetween('start_time', [$fromDate, $toDate]);
        
        // Paid appointments for revenue calculation
        $paidAppointmentsQuery = (clone $appointmentsQuery)
            ->where('payment_status', 'paid');

        // Summary Statistics
        $totalAppointments = $appointmentsQuery->count();
        $completedAppointments = (clone $appointmentsQuery)->where('status', 'completed')->count();
        $cancelledAppointments = (clone $appointmentsQuery)->where('status', 'cancelled')->count();
        $noShowAppointments = (clone $appointmentsQuery)->where('status', 'no_show')->count();
        $pendingAppointments = (clone $appointmentsQuery)->where('status', 'pending')->count();
        $inProgressAppointments = (clone $appointmentsQuery)->where('status', 'in_progress')->count();
        
        $totalRevenue = $paidAppointmentsQuery->sum('total_amount');
        $averageOrderValue = $totalAppointments > 0 
            ? round($totalRevenue / $totalAppointments, 2) 
            : 0;
        
        // Today's statistics
        $today = now()->startOfDay();
        $appointmentsToday = Appointment::where('vendor_id', $vendorId)
            ->whereDate('start_time', $today)
            ->count();
        $revenueToday = Appointment::where('vendor_id', $vendorId)
            ->whereDate('start_time', $today)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Appointment Status Breakdown
        $statusBreakdown = (clone $appointmentsQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(function ($item) use ($totalAppointments) {
                $statusLabels = [
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                    'no_show' => 'No Show'
                ];
                return [
                    'status' => $statusLabels[$item->status] ?? ucfirst($item->status),
                    'count' => $item->count,
                    'percentage' => $totalAppointments > 0 
                        ? round(($item->count / $totalAppointments) * 100, 1) 
                        : 0
                ];
            });

        // Payment Status Breakdown
        $paymentStatusBreakdown = (clone $appointmentsQuery)
            ->select('payment_status', DB::raw('count(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => ucfirst($item->payment_status),
                    'count' => $item->count,
                    'total' => (float) $item->total
                ];
            });

        // Revenue Trend (grouped by month)
        $revenueTrend = (clone $paidAppointmentsQuery)
            ->select(
                DB::raw("strftime('%Y-%m', start_time) as month"),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as appointment_count')
            )
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M Y', strtotime($item->month . '-01')),
                    'revenue' => (float) $item->revenue,
                    'appointments' => (int) $item->appointment_count
                ];
            });

        // For date ranges of 31 days or less, show daily breakdown
        $daysDiff = $fromDate->diffInDays($toDate);
        if ($daysDiff <= 31) {
            $revenueTrend = (clone $paidAppointmentsQuery)
                ->select(
                    DB::raw("strftime('%Y-%m-%d', start_time) as day"),
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('COUNT(*) as appointment_count')
                )
                ->groupBy('day')
                ->orderBy('day', 'ASC')
                ->get()
                ->map(function ($item) {
                    return [
                        'month' => date('M d', strtotime($item->day)),
                        'revenue' => (float) $item->revenue,
                        'appointments' => (int) $item->appointment_count
                    ];
                });
        }

        // Popular Services for this vendor
        $popularServices = Service::select('services.id', 'services.name', DB::raw('COUNT(appointments.id) as total_bookings'))
            ->leftJoin('appointments', 'services.id', '=', 'appointments.service_id')
            ->where('appointments.vendor_id', $vendorId)
            ->whereBetween('appointments.start_time', [$fromDate, $toDate])
            ->groupBy('services.id', 'services.name')
            ->orderBy('total_bookings', 'DESC')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'total' => (int) $item->total_bookings
                ];
            });

        // Staff performance for this vendor
        $staffPerformance = Staff::select('staff.id', 'staff.name', 
            DB::raw('COUNT(appointments.id) as total_appointments'),
            DB::raw('SUM(CASE WHEN appointments.payment_status = "paid" THEN appointments.total_amount ELSE 0 END) as total_revenue'),
            DB::raw('AVG(CASE WHEN appointments.payment_status = "paid" THEN appointments.total_amount ELSE NULL END) as avg_order_value')
        )
        ->leftJoin('appointments', 'staff.id', '=', 'appointments.staff_id')
        ->where('staff.vendor_id', $vendorId)
        ->whereBetween('appointments.start_time', [$fromDate, $toDate])
        ->groupBy('staff.id', 'staff.name')
        ->orderBy('total_appointments', 'DESC')
        ->limit(5)
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'total_appointments' => (int) $item->total_appointments,
                'total_revenue' => (float) $item->total_revenue,
                'avg_order_value' => (float) $item->avg_order_value
            ];
        });

        // Hourly distribution (peak hours)
        $hourlyDistribution = (clone $appointmentsQuery)
            ->select(
                DB::raw("strftime('%H', start_time) as hour"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => (int) $item->hour,
                    'label' => date('g:i A', strtotime("{$item->hour}:00")),
                    'appointments' => (int) $item->count
                ];
            });

        // Weekly distribution
        $weeklyDistribution = (clone $appointmentsQuery)
            ->select(
                DB::raw("strftime('%w', start_time) as day_of_week"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day_of_week')
            ->orderBy('day_of_week', 'ASC')
            ->get()
            ->map(function ($item) {
                $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                return [
                    'day' => $dayNames[(int)$item->day_of_week],
                    'appointments' => (int) $item->count
                ];
            });

        // Customer statistics for this vendor
        $totalCustomers = (clone $appointmentsQuery)->distinct('customer_id')->count('customer_id');
        $repeatCustomers = (clone $appointmentsQuery)
            ->select('customer_id', DB::raw('COUNT(*) as appointment_count'))
            ->groupBy('customer_id')
            ->having('appointment_count', '>', 1)
            ->get()
            ->count();

        // Top customers
        $topCustomers = (clone $appointmentsQuery)
            ->select(
                'customer_id',
                DB::raw('COUNT(*) as total_appointments'),
                DB::raw('SUM(total_amount) as total_spent')
            )
            ->with('customer:id,name,email')
            ->groupBy('customer_id')
            ->orderBy('total_appointments', 'DESC')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->customer_id,
                    'name' => $item->customer->name ?? 'Unknown',
                    'email' => $item->customer->email ?? 'N/A',
                    'total_appointments' => (int) $item->total_appointments,
                    'total_spent' => (float) $item->total_spent
                ];
            });

        // Recurring appointments
        $recurringAppointments = (clone $appointmentsQuery)
            ->where('is_recurring', true)
            ->count();
        
        $waitlistAppointments = (clone $appointmentsQuery)
            ->where('is_waitlist', true)
            ->count();

        // Cancellation rate
        $cancellationRate = $totalAppointments > 0 
            ? round(($cancelledAppointments / $totalAppointments) * 100, 1) 
            : 0;

        // No-show rate
        $noShowRate = $totalAppointments > 0 
            ? round(($noShowAppointments / $totalAppointments) * 100, 1) 
            : 0;

        // Completion rate
        $completionRate = $totalAppointments > 0 
            ? round((($totalAppointments - $cancelledAppointments - $noShowAppointments) / $totalAppointments) * 100, 1) 
            : 0;

        // Upcoming appointments
        $upcomingAppointments = Appointment::where('vendor_id', $vendorId)
            ->where('start_time', '>', now())
            ->whereNotIn('status', ['cancelled', 'completed', 'no_show'])
            ->count();

        return response()->json([
            'summary' => [
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'pending_appointments' => $pendingAppointments,
                'in_progress_appointments' => $inProgressAppointments,
                'cancelled_appointments' => $cancelledAppointments,
                'no_show_appointments' => $noShowAppointments,
                'total_revenue' => number_format($totalRevenue, 2),
                'average_order_value' => number_format($averageOrderValue, 2),
                'appointments_today' => $appointmentsToday,
                'revenue_today' => number_format($revenueToday, 2),
                'total_customers' => $totalCustomers,
                'repeat_customers' => $repeatCustomers,
                'recurring_appointments' => $recurringAppointments,
                'waitlist_appointments' => $waitlistAppointments,
                'upcoming_appointments' => $upcomingAppointments,
                'cancellation_rate' => $cancellationRate,
                'no_show_rate' => $noShowRate,
                'completion_rate' => $completionRate,
            ],
            'status_breakdown' => $statusBreakdown,
            'payment_status_breakdown' => $paymentStatusBreakdown,
            'revenue_trend' => $revenueTrend,
            'popular_services' => $popularServices,
            'staff_performance' => $staffPerformance,
            'top_customers' => $topCustomers,
            'hourly_distribution' => $hourlyDistribution,
            'weekly_distribution' => $weeklyDistribution,
            'date_range' => [
                'from' => $fromDate->format('Y-m-d'),
                'to' => $toDate->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Export vendor's report to CSV
     */
    public function export(Request $request)
    {
        $vendorId = auth()->user()->vendor->id;
        
        if (!$vendorId) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $fromDate = $request->get('from_date') 
            ? now()->parse($request->from_date)->startOfDay() 
            : now()->subDays(30)->startOfDay();
        
        $toDate = $request->get('to_date') 
            ? now()->parse($request->to_date)->endOfDay() 
            : now()->endOfDay();

        // Get detailed appointment data for export
        $appointments = Appointment::with(['customer', 'service', 'staff'])
            ->where('vendor_id', $vendorId)
            ->whereBetween('start_time', [$fromDate, $toDate])
            ->orderBy('start_time', 'DESC')
            ->get();

        // Generate CSV
        $csvFileName = "vendor_report_{$fromDate->format('Y-m-d')}_to_{$toDate->format('Y-m-d')}.csv";
        $handle = fopen('php://temp', 'w+');

        // Add UTF-8 BOM for Excel compatibility
        fwrite($handle, "\xEF\xBB\xBF");

        // Add CSV headers
        fputcsv($handle, [
            'Booking Number',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Service Name',
            'Staff Name',
            'Start Time',
            'End Time',
            'Status',
            'Payment Status',
            'Total Amount',
            'Payment Method',
            'Transaction ID',
            'Is Recurring',
            'Is Waitlist',
            'Customer Notes',
            'Created At'
        ]);

        // Add data rows
        foreach ($appointments as $appointment) {
            fputcsv($handle, [
                $appointment->booking_number,
                $appointment->customer->name ?? 'N/A',
                $appointment->customer->email ?? 'N/A',
                $appointment->customer->phone ?? 'N/A',
                $appointment->service->name ?? 'N/A',
                $appointment->staff->name ?? 'N/A',
                $appointment->start_time,
                $appointment->end_time,
                $appointment->status,
                $appointment->payment_status,
                $appointment->total_amount,
                $appointment->payment_method ?? 'N/A',
                $appointment->transaction_id ?? 'N/A',
                $appointment->is_recurring ? 'Yes' : 'No',
                $appointment->is_waitlist ? 'Yes' : 'No',
                $appointment->customer_notes ?? 'N/A',
                $appointment->created_at
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$csvFileName}\"");
    }

    /**
     * Get quick overview for vendor dashboard
     */
    public function overview(Request $request)
    {
        $vendorId = auth()->user()->vendor_id;
        
        if (!$vendorId) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        // Current vs last month comparison
        $currentMonthAppointments = Appointment::where('vendor_id', $vendorId)
            ->whereYear('start_time', now()->year)
            ->whereMonth('start_time', now()->month)
            ->count();
        
        $lastMonthAppointments = Appointment::where('vendor_id', $vendorId)
            ->whereYear('start_time', $lastMonth->year)
            ->whereMonth('start_time', $lastMonth->month)
            ->count();

        $currentMonthRevenue = Appointment::where('vendor_id', $vendorId)
            ->whereYear('start_time', now()->year)
            ->whereMonth('start_time', now()->month)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $lastMonthRevenue = Appointment::where('vendor_id', $vendorId)
            ->whereYear('start_time', $lastMonth->year)
            ->whereMonth('start_time', $lastMonth->month)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $appointmentGrowth = $lastMonthAppointments > 0 
            ? round((($currentMonthAppointments - $lastMonthAppointments) / $lastMonthAppointments) * 100, 1)
            : 0;

        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        return response()->json([
            'today' => [
                'appointments' => Appointment::where('vendor_id', $vendorId)->whereDate('start_time', $today)->count(),
                'completed' => Appointment::where('vendor_id', $vendorId)->whereDate('start_time', $today)->where('status', 'completed')->count(),
                'revenue' => (float) Appointment::where('vendor_id', $vendorId)->whereDate('start_time', $today)->where('payment_status', 'paid')->sum('total_amount'),
            ],
            'this_week' => [
                'appointments' => Appointment::where('vendor_id', $vendorId)->whereBetween('start_time', [$thisWeek, now()])->count(),
                'revenue' => (float) Appointment::where('vendor_id', $vendorId)->whereBetween('start_time', [$thisWeek, now()])->where('payment_status', 'paid')->sum('total_amount'),
            ],
            'this_month' => [
                'appointments' => $currentMonthAppointments,
                'revenue' => (float) $currentMonthRevenue,
            ],
            'last_month' => [
                'appointments' => $lastMonthAppointments,
                'revenue' => (float) $lastMonthRevenue,
            ],
            'growth' => [
                'appointments' => $appointmentGrowth,
                'revenue' => $revenueGrowth,
            ],
        ]);
    }

    /**
     * Get staff performance report for vendor
     */
    public function staffReport(Request $request)
    {
        $vendorId = auth()->user()->vendor->id;
        
        if (!$vendorId) {
            return response()->json(['error' => 'Vendor not found'], 404);
        }

        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'staff_id' => 'nullable|exists:staff,id'
        ]);

        $fromDate = $request->get('from_date') 
            ? now()->parse($request->from_date)->startOfDay() 
            : now()->subDays(30)->startOfDay();
        
        $toDate = $request->get('to_date') 
            ? now()->parse($request->to_date)->endOfDay() 
            : now()->endOfDay();

        $staffQuery = Staff::where('vendor_id', $vendorId);
        
        if ($request->has('staff_id')) {
            $staffQuery->where('id', $request->staff_id);
        }

        $staffPerformance = $staffQuery->get()->map(function ($staff) use ($fromDate, $toDate) {
            $appointments = Appointment::where('staff_id', $staff->id)
                ->whereBetween('start_time', [$fromDate, $toDate]);
            
            $totalAppointments = (clone $appointments)->count();
            $completedAppointments = (clone $appointments)->where('status', 'completed')->count();
            $totalRevenue = (clone $appointments)->where('payment_status', 'paid')->sum('total_amount');
            
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'email' => $staff->email,
                'phone' => $staff->phone,
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'completion_rate' => $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100, 1) : 0,
                'total_revenue' => (float) $totalRevenue,
                'average_order_value' => $totalAppointments > 0 ? round($totalRevenue / $totalAppointments, 2) : 0,
            ];
        });

        return response()->json([
            'staff_performance' => $staffPerformance,
            'date_range' => [
                'from' => $fromDate->format('Y-m-d'),
                'to' => $toDate->format('Y-m-d'),
            ]
        ]);
    }
}