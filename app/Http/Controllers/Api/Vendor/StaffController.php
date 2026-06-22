<?php

namespace App\Http\Controllers\Api\Vendor;
use App\Http\Controllers\Api\BaseApiController;

use App\Models\Staff;
use App\VendorLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends BaseApiController
{
    use VendorLimitTrait;

    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;
        try {
            $staff = Staff::with('services')->where('vendor_id', $request->user()->vendor->id)
                ->paginate($request->per_page ?? 15);

            return response()->json(['data' => $staff, 'limit' => $this->checkStaffLimit($vendor)]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $this->checkStaffLimit($vendor);
        
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'position' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'exists:services,id',
            ]);

            $staff = Staff::create([
                ...$validated,
                'vendor_id' => $request->user()->vendor->id,
            ]);

            $staff->services()->sync($validated['service_ids'] ?? []);

            return $this->success($staff, 'Staff member created', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Staff $staff)
    {
        return $this->success($staff, 'Staff retrieved successfully');
    }

    public function update(Request $request, Staff $staff)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'position' => 'nullable|string',
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'exists:services,id',
            ]);

            $staff->update($validated);

            $staff->services()->sync($validated['service_ids'] ?? []);

            return $this->success($staff, 'Staff member updated');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Staff $staff)
    {
        try {
            $staff->delete();
            return $this->success([], 'Staff member deleted');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
