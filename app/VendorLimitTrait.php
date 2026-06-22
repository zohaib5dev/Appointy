<?php

namespace App;

use App\Models\Plan;

trait VendorLimitTrait
{
    protected function getVendorLimits($vendor)
    {
        // Check if vendor has a plan
        if ($vendor->plan_id) {
            $plan = Plan::find($vendor->plan_id);
            if ($plan) {
                return [
                    'services_limit' => $plan->services_limit,
                    'staff_limit' => $plan->staff_limit,
                    'appointments_limit' => $plan->appointments_limit,
                    'storage_limit' => $plan->storage_limit,
                ];
            }
        }

        // Default limits for trial
        return [
            'services_limit' => 5,
            'staff_limit' => 3,
            'appointments_limit' => 50,
            'storage_limit' => 5000,
        ];
    }

    protected function checkServiceLimit($vendor)
    {
        $limits = $this->getVendorLimits($vendor);
        $currentCount = $vendor->services()->count();

        if ($currentCount >= $limits['services_limit']) {
            return [
                'message' => "You have reached your service limit of {$limits['services_limit']}. Please upgrade your plan.",
                'limitReached' => true
            ]; 
        }
    }

    protected function checkStaffLimit($vendor)
    {
        $limits = $this->getVendorLimits($vendor);
        $currentCount = $vendor->staff()->count();

        if ($currentCount >= $limits['staff_limit']) {
            return [
                'message' => "You have reached your staff limit of {$limits['staff_limit']}. Please upgrade your plan.",
                'limitReached' => true
            ];
        }
    }

    protected function checkAppointmentLimit($vendor)
    {
        $limits = $this->getVendorLimits($vendor);
        $currentCount = $vendor->appointments()->whereMonth('created_at', now()->month)->count();

        if ($currentCount >= $limits['appointments_limit']) {
            abort(403, "You have reached your monthly appointment limit of {$limits['appointments_limit']}. Please upgrade your plan.");
        }
    }
}
