<?php


namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;

use App\Models\Schedule;
use App\Models\BreakTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $vendor = Auth::user()->vendor;
            
            $schedules = Schedule::where('vendor_id', $vendor->id)->get();
            
            // Load breaks for each schedule day
            foreach ($schedules as $schedule) {
                $schedule->breaks = BreakTime::where('vendor_id', $vendor->id)
                    ->where('day_of_week', $schedule->day)
                    ->get();
            }
            
            return response()->json(['data' => $schedules]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $vendor = Auth::user()->vendor;
            $savedSchedules = [];
            
            foreach ($request->all() as $day => $data) {
                if (in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])) {
                    
                    // Handle schedule
                    if ($data['active']) {
                        $schedule = Schedule::updateOrCreate(
                            ['vendor_id' => $vendor->id, 'day' => $day],
                            [
                                'start_time' => $data['start'],
                                'end_time' => $data['end'],
                                'is_active' => true,
                            ]
                        );
                        $savedSchedules[] = $schedule;
                    } else {
                        // If not active, delete the schedule
                        Schedule::where('vendor_id', $vendor->id)
                            ->where('day', $day)
                            ->delete();
                    }
                    
                    // Handle breaks for this day
                    if (isset($data['breaks']) && is_array($data['breaks'])) {
                        // First delete existing breaks for this day
                        BreakTime::where('vendor_id', $vendor->id)
                            ->where('day_of_week', $day)
                            ->delete();
                        
                        // Then create new breaks
                        foreach ($data['breaks'] as $break) {
                            if (isset($break['start_time']) && isset($break['end_time'])) {
                                BreakTime::create([
                                    'vendor_id' => $vendor->id,
                                    'day_of_week' => $day,
                                    'start_time' => $break['start_time'],
                                    'end_time' => $break['end_time'],
                                    'is_active' => true,
                                ]);
                            }
                        }
                    }
                }
            }
            
            return $this->success($savedSchedules, 'Schedule and breaks updated successfully', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Schedule $schedule)
    {
        try {
            $vendor = Auth::user()->vendor;
            
            if ($schedule->vendor_id !== $vendor->id) {
                return $this->error('Unauthorized', 403);
            }
            
            $schedule->breaks = BreakTime::where('vendor_id', $vendor->id)
                ->where('day_of_week', $schedule->day)
                ->get();
                
            return $this->success($schedule, 'Schedule retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, Schedule $schedule)
    {
        try {
            $vendor = Auth::user()->vendor;
            
            if ($schedule->vendor_id !== $vendor->id) {
                return $this->error('Unauthorized', 403);
            }
            
            $validated = $request->validate([
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'is_active' => 'boolean',
                'breaks' => 'nullable|array',
                'breaks.*.start_time' => 'required_with:breaks|date_format:H:i',
                'breaks.*.end_time' => 'required_with:breaks|date_format:H:i|after:breaks.*.start_time',
            ]);

            $schedule->update([
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
            
            // Update breaks if provided
            if (isset($validated['breaks'])) {
                // Delete existing breaks for this day
                BreakTime::where('vendor_id', $vendor->id)
                    ->where('day_of_week', $schedule->day)
                    ->delete();
                
                // Create new breaks
                foreach ($validated['breaks'] as $break) {
                    if (isset($break['start_time']) && isset($break['end_time'])) {
                        BreakTime::create([
                            'vendor_id' => $vendor->id,
                            'day_of_week' => $schedule->day,
                            'start_time' => $break['start_time'],
                            'end_time' => $break['end_time'],
                            'is_active' => true,
                        ]);
                    }
                }
            }
            
            $schedule->breaks = BreakTime::where('vendor_id', $vendor->id)
                ->where('day_of_week', $schedule->day)
                ->get();
            
            return $this->success($schedule, 'Schedule updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            $vendor = Auth::user()->vendor;
            
            if ($schedule->vendor_id !== $vendor->id) {
                return $this->error('Unauthorized', 403);
            }
            
            // Delete associated breaks first
            BreakTime::where('vendor_id', $vendor->id)
                ->where('day_of_week', $schedule->day)
                ->delete();
            
            // Delete the schedule
            $schedule->delete();
            
            return $this->success(null, 'Schedule deleted successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

}

