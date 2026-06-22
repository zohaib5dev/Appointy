<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HolidayController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $query = Holiday::query();
            
            // Automatically filter by vendor if not admin
            $vendorId = $request->vendor_id ?? Auth::user()->vendor->id;
            if ($vendorId) {
                $query->where('vendor_id', $vendorId);
            }
            
            // Filter by staff if specified
            if ($request->staff_id) {
                $query->where('staff_id', $request->staff_id);
            }
            
            // Apply filters
            if ($request->search) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            
            if ($request->from_date) {
                $query->where('end_date', '>=', $request->from_date);
            }
            
            if ($request->to_date) {
                $query->where('start_date', '<=', $request->to_date);
            }
            
            if ($request->year) {
                $query->whereYear('start_date', $request->year)
                      ->orWhereYear('end_date', $request->year);
            }
            
            if ($request->month) {
                $query->whereMonth('start_date', $request->month)
                      ->orWhereMonth('end_date', $request->month);
            }
            
            $holidays = $query->orderBy('start_date', 'asc')->paginate($request->per_page ?? 15);
            
            // Transform data to include duration
            $holidays->getCollection()->transform(function ($holiday) {
                $start = Carbon::parse($holiday->start_date);
                $end = Carbon::parse($holiday->end_date);
                $holiday->duration = $start->diffInDays($end) + 1;
                $holiday->is_multi_day = $holiday->duration > 1;
                return $holiday;
            });

            return response()->json($holidays);
            
            return $this->success($holidays, 'Holidays retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'staff_id' => 'nullable|exists:staff,id',
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $validated['vendor_id'] = Auth::user()->vendor->id;
            
            // Check for overlapping holidays
            $overlapping = Holiday::where('vendor_id', $validated['vendor_id'])
                ->when($validated['staff_id'] ?? null, function ($query, $staffId) {
                    return $query->where('staff_id', $staffId);
                })
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                          ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                          ->orWhere(function ($q) use ($validated) {
                              $q->where('start_date', '<=', $validated['start_date'])
                                ->where('end_date', '>=', $validated['end_date']);
                          });
                })
                ->exists();
                
            if ($overlapping) {
                return $this->error('Holiday overlaps with an existing holiday', 422);
            }
            
            $holiday = Holiday::create($validated);
            return $this->success($holiday, 'Holiday created successfully', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    
    public function show(Holiday $holiday)
    {
        // Ensure vendor can only view their own holidays
        if ($holiday->vendor_id !== Auth::user()->vendor->id) {
            return $this->error('Unauthorized', 403);
        }
        
        return $this->success($holiday, 'Holiday retrieved successfully');
    }
    
    public function update(Request $request, Holiday $holiday)
    {
        try {
            // Ensure vendor can only update their own holidays
            if ($holiday->vendor_id !== Auth::user()->vendor->id) {
                return $this->error('Unauthorized', 403);
            }
            
            $validated = $request->validate([
                'staff_id' => 'nullable|exists:staff,id',
                'title' => 'sometimes|string|max:255',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);
            
            // Check for overlapping holidays excluding current
            if (isset($validated['start_date']) || isset($validated['end_date'])) {
                $startDate = $validated['start_date'] ?? $holiday->start_date;
                $endDate = $validated['end_date'] ?? $holiday->end_date;
                
                $overlapping = Holiday::where('vendor_id', $holiday->vendor_id)
                    ->where('id', '!=', $holiday->id)
                    ->when($validated['staff_id'] ?? $holiday->staff_id, function ($query, $staffId) {
                        return $query->where('staff_id', $staffId);
                    })
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('start_date', [$startDate, $endDate])
                              ->orWhereBetween('end_date', [$startDate, $endDate])
                              ->orWhere(function ($q) use ($startDate, $endDate) {
                                  $q->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                              });
                    })
                    ->exists();
                    
                if ($overlapping) {
                    return $this->error('Holiday overlaps with an existing holiday', 422);
                }
            }
            
            $holiday->update($validated);
            return $this->success($holiday, 'Holiday updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    
    public function destroy(Holiday $holiday)
    {
        try {
            // Ensure vendor can only delete their own holidays
            if ($holiday->vendor_id !== Auth::user()->vendor->id) {
                return $this->error('Unauthorized', 403);
            }
            
            $holiday->delete();
            return $this->success(null, 'Holiday deleted successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    
    // Get holidays for calendar (returns expanded dates)
    public function calendar(Request $request)
    {
        try {
            $vendorId = $request->vendor_id ?? Auth::user()->vendor->id;
            $year = $request->year ?? now()->year;
            $month = $request->month ?? now()->month;
            
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            
            $holidays = Holiday::where('vendor_id', $vendorId)
                ->where('is_active', true)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                })
                ->get();
                
            // Expand date ranges for calendar display
            $calendarHolidays = [];
            foreach ($holidays as $holiday) {
                $start = Carbon::parse($holiday->start_date);
                $end = Carbon::parse($holiday->end_date);
                $current = $start->copy();
                
                while ($current <= $end) {
                    $calendarHolidays[] = [
                        'date' => $current->format('Y-m-d'),
                        'holiday_id' => $holiday->id,
                        'title' => $holiday->title,
                        'description' => $holiday->description,
                        'staff_id' => $holiday->staff_id,
                        'is_multi_day' => true
                    ];
                    $current->addDay();
                }
            }
            
            return response()->json([
                'holidays' => $holidays,
                'calendar_dates' => $calendarHolidays,
                'year' => $year,
                'month' => $month
            ], 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}