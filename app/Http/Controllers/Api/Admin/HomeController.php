<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Appointment;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_vendors' => Vendor::count(),
            'active_vendors' => Vendor::where('status', 'active')->count(),
            'pending_vendors' => Vendor::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_appointments' => Appointment::count(),
            'today_appointments' => Appointment::whereDate('start_time', today())->count(),
            'total_revenue' => Appointment::where('payment_status', 'paid')->sum('total_amount'),
            'monthly_revenue' => Appointment::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        $plans = Plan::with('prices')->get();

        $recent_vendors = Vendor::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recent_appointments = Appointment::with(['vendor', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'stats' => $stats,
            'plans' => $plans,
            'recent_vendors' => $recent_vendors,
            'recent_appointments' => $recent_appointments,
        ]);
    }

    public function appointments(Request $request)
    {
        $query = Appointment::with(['vendor', 'customer', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $appointments = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($appointments);
    }

    public function appointment($id)
    {
        $query = Appointment::with(['vendor', 'customer', 'service'])->where('id', $id);

        $appointment = $query->firstOrFail();

        return response()->json($appointment);
    }

    public function getReports(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $revenueByVendor = DB::table('appointments')
            ->join('vendors', 'appointments.vendor_id', '=', 'vendors.id')
            ->select('vendors.business_name', DB::raw('SUM(total_amount) as revenue'))
            ->where('payment_status', 'paid')
            ->groupBy('vendors.id', 'vendors.business_name')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get();

        $appointmentsByStatus = DB::table('appointments')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $popularServices = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('COUNT(*) as total'))
            ->groupBy('services.id', 'services.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'revenue_by_vendor' => $revenueByVendor,
            'appointments_by_status' => $appointmentsByStatus,
            'popular_services' => $popularServices,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

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

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }
}
