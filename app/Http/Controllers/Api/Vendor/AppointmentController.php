<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\BreakTime;
use App\Models\Holiday;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends BaseApiController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::with(['service', 'vendor', 'staff', 'customer'])->where('vendor_id', $user->vendor->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_time', '<=', $request->date_to);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        $appointments = $query->orderBy('start_time', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($appointments);
    }

    public function show($id)
    {
        $user = auth()->user();
        $appointment = Appointment::with(['service', 'vendor', 'staff', 'customer'])
            ->findOrFail($id);

        // Check authorization
        if (
            !$user->isAdmin() &&
            $appointment->customer_id !== $user->id &&
            ($user->isVendor() && $appointment->vendor_id !== $user->vendor->id)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($appointment);
    }

    public function cancel($id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);

        // Check authorization
        if (
            !$user->isAdmin() &&
            $appointment->customer_id !== $user->id &&
            ($user->isVendor() && $appointment->vendor_id !== $user->vendor->id)
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return response()->json(['message' => 'Cannot cancel this appointment'], 422);
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Appointment cancelled successfully',
            'appointment' => $appointment
        ]);
    }

    public function reschedule(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);

        if ($appointment->customer_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $newStartTime = Carbon::parse($request->start_time);
        $newEndTime = $newStartTime->copy()->addMinutes($appointment->service->duration);

        $isAvailable = $this->availabilityService->checkAvailability(
            $appointment->vendor_id,
            $appointment->service,
            $newStartTime,
            $newEndTime,
            $appointment->staff_id
        );

        if (!$isAvailable) {
            return response()->json(['message' => 'New time slot is not available'], 422);
        }

        $appointment->update([
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment rescheduled successfully',
            'appointment' => $appointment
        ]);
    }


    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);

        $appointment->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Appointment status updated successfully',
        ]);
    }

    public function updateStaff(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);

        $appointment->update(['staff_id' => $request->staff_id]);

        return response()->json([
            'message' => 'Appointment staff updated successfully',
        ]);
    }

    public function updateNotes(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);

        $appointment->update(['vendor_notes' => $request->vendor_notes]);

        return response()->json([
            'message' => 'Appointment notes updated successfully',
        ]);
    }

    public function sendReminder(Request $request, $id)
    {
        $user = auth()->user();
        $appointment = Appointment::findOrFail($id);


        return response()->json([
            'message' => 'Reminder sent successfully',
        ]);
    }
}
