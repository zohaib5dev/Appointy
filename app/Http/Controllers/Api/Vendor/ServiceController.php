<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\VendorLimitTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends BaseApiController
{
    use VendorLimitTrait;

    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $query = Service::with(['vendor', 'category'])->where('vendor_id', Auth::user()->vendor->id);

        if ($category = $request->query('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $category));
        }

        return response()->json(['data' => $query->paginate(16),  'limit' => $this->checkServiceLimit($vendor)]);
    }

    public function store(ServiceRequest $request)
    {
        $vendor = Auth::user()->vendor;

        $this->checkServiceLimit($vendor);

        $data = $request->validated();
        $data['vendor_id'] = $vendor->id;
        $data['slug'] =  \Str::slug($data['name']);

        $service = Service::create($data);

        return $this->success($service, 'Service created successfully', 201);
    }

    public function show(Service $service)
    {
        return $this->success($service->load(['vendor', 'category']));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        return $this->success($service, 'Service updated successfully');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return $this->success([], 'Service deleted successfully');
    }

    public function categories()
    {
        $categories = ServiceCategory::where('is_active', 1)->paginate(20);

        return response()->json([
            'categories' => $categories,
        ]);
    }
}
