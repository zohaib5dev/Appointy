<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\BreakTime;
use App\Models\Holiday;
use Carbon\Carbon;

class AvailabilityService
{
    public function getAvailableSlots($vendorId, Service $service, Carbon $date, $staffId = null)
    {
        // Check if vendor is closed on this date
        if ($this->isHoliday($vendorId, $date, $staffId)) {
            return 633;
        }

        // Get working hours for the day
        $workingHours = $this->getWorkingHours($vendorId, $date, $staffId);
        
        if (empty($workingHours)) {
            return [];
        }

        // Get existing appointments
        $existingAppointments = $this->getExistingAppointments($vendorId, $date, $staffId);

        // Get break times
        $breaks = $this->getBreaks($vendorId, $date, $staffId);

        $slots = [];
        $interval = $service->duration;
        
        foreach ($workingHours as $period) {
            $start = Carbon::parse($date->format('Y-m-d') . ' ' . $period->start_time);
            $end = Carbon::parse($date->format('Y-m-d') . ' ' . $period->end_time);
            
            $current = $start->copy();
            
            while ($current->copy()->addMinutes($interval) <= $end) {
                $slotEnd = $current->copy()->addMinutes($interval);
                
                // Check if slot is available
                $isAvailable = $this->isSlotAvailable(
                    $current,
                    $slotEnd,
                    $existingAppointments,
                    $breaks,
                    $service->buffer_before ?? 0,
                    $service->buffer_after ?? 0
                );
                
                if ($isAvailable && $current > now()) {
                    $slots[] = [
                        'start_time' => $current->toDateTimeString(),
                        'end_time' => $slotEnd->toDateTimeString(),
                        'formatted_time' => $current->format('g:i A'),
                    ];
                }
                
                $current->addMinutes($interval);
            }
        }
        
        return $slots;
    }
    
    public function checkAvailability($vendorId, Service $service, Carbon $startTime, Carbon $endTime, $staffId = null)
    {

        $date = $startTime->copy()->startOfDay();
        
        // Check holiday
        if ($this->isHoliday($vendorId, $date, $staffId)) {
            return false;
        }
        
        // Check working hours
        if (!$this->isWithinWorkingHours($vendorId, $startTime, $endTime, $staffId)) {
            return false;
        }
        
        // Check existing appointments
        $conflictingAppointments = Appointment::where('vendor_id', $vendorId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            });
            
        if ($staffId) {
            $conflictingAppointments->where('staff_id', $staffId);
        }
        
        if ($conflictingAppointments->exists()) {
            return false;
        }
        
        // Check break times
        if ($this->isBreakTime($vendorId, $startTime, $endTime, $staffId)) {
            return false;
        }
        
        return true;
    }
    
    protected function getWorkingHours($vendorId, Carbon $date, $staffId = null)
    {
        $dayOfWeek = strtolower($date->format('l'));
        
        $query = Schedule::where('vendor_id', $vendorId)
            ->where('day', $dayOfWeek)
            ->where('is_active', true);
            
            $query->whereNull('staff_id');
        // if ($staffId) {
        //     $query->where('staff_id', $staffId);
        // } else {
        // }
        
        return $query->get();
    }
    
    protected function getExistingAppointments($vendorId, Carbon $date, $staffId = null)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();
        
        $query = Appointment::where('vendor_id', $vendorId)
            ->whereBetween('start_time', [$startOfDay, $endOfDay])
            ->where('status', '!=', 'cancelled');
            
        if ($staffId) {
            $query->where('staff_id', $staffId);
        }
        
        return $query->get();
    }
    
    protected function getBreaks($vendorId, Carbon $date, $staffId = null)
    {
        $dayOfWeek = strtolower($date->format('l'));
        
        $query = BreakTime::where('vendor_id', $vendorId)
            ->where('day', $dayOfWeek)
            ->where('is_active', true);
            
        if ($staffId) {
            $query->where('staff_id', $staffId);
        } else {
            $query->whereNull('staff_id');
        }
        
        return $query->get();
    }
    
    protected function isSlotAvailable($start, $end, $existingAppointments, $breaks, $bufferBefore = 0, $bufferAfter = 0)
    {
        // Check existing appointments with buffer
        foreach ($existingAppointments as $appointment) {
            $appointmentStart = Carbon::parse($appointment->start_time);
            $appointmentEnd = Carbon::parse($appointment->end_time);
            
            // Add buffer time
            $bufferStart = $appointmentStart->copy()->subMinutes($bufferBefore);
            $bufferEnd = $appointmentEnd->copy()->addMinutes($bufferAfter);
            
            if ($start->between($bufferStart, $bufferEnd) || 
                $end->between($bufferStart, $bufferEnd) ||
                ($start <= $bufferStart && $end >= $bufferEnd)) {
                return false;
            }
        }
        
        // Check breaks
        foreach ($breaks as $break) {
            $breakStart = Carbon::parse($start->format('Y-m-d') . ' ' . $break->start_time);
            $breakEnd = Carbon::parse($start->format('Y-m-d') . ' ' . $break->end_time);
            
            if ($start->between($breakStart, $breakEnd) || 
                $end->between($breakStart, $breakEnd) ||
                ($start <= $breakStart && $end >= $breakEnd)) {
                return false;
            }
        }
        
        return true;
    }
    
    protected function isHoliday($vendorId, Carbon $date, $staffId = null)
    {
        $query = Holiday::where('vendor_id', $vendorId)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('is_active', true);
            
        if ($staffId) {
            $query->where(function ($q) use ($staffId) {
                $q->where('staff_id', $staffId)
                  ->orWhereNull('staff_id');
            });
        }
        
        return $query->exists();
    }
    
    protected function isWithinWorkingHours($vendorId, Carbon $startTime, Carbon $endTime, $staffId = null)
    {
        $workingHours = $this->getWorkingHours($vendorId, $startTime, $staffId);
        
        $startTimeStr = $startTime->format('H:i:s');
        $endTimeStr = $endTime->format('H:i:s');
        
        foreach ($workingHours as $hours) {
            if ($startTimeStr >= $hours->start_time && $endTimeStr <= $hours->end_time) {
                return true;
            }
        }
        
        return false;
    }
    
    protected function isBreakTime($vendorId, Carbon $startTime, Carbon $endTime, $staffId = null)
    {
        $breaks = $this->getBreaks($vendorId, $startTime, $staffId);
        
        $startTimeStr = $startTime->format('H:i:s');
        $endTimeStr = $endTime->format('H:i:s');
        
        foreach ($breaks as $break) {
            if ($startTimeStr >= $break->start_time && $endTimeStr <= $break->end_time) {
                return true;
            }
            
            if ($startTimeStr < $break->start_time && $endTimeStr > $break->start_time) {
                return true;
            }
        }
        
        return false;
    }
}