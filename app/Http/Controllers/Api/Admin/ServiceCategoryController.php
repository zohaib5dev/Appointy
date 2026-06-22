<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Support\Facades\Validator;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends BaseApiController
{
    public function index()
    {
        $categories = ServiceCategory::with('services')->orderBy('order')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:service_categories',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = ServiceCategory::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'order' => $request->order ?? 0,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function show(ServiceCategory $category)
    {
        return $this->success($category);
    }

    public function update(Request $request, $id)
    {
        $category = ServiceCategory::findOrFail($id);

        $category->update($request->only([
            'name',
            'description',
            'icon',
            'color',
            'order',
            'is_active'
        ]));

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = ServiceCategory::findOrFail($id);

        // Check if category has services
        if ($category->services()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with existing services'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
