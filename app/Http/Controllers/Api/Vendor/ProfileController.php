<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends BaseApiController
{
    public function show()
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return $this->error('Vendor not found', 404);
        }
        
        // Decode JSON fields
        $vendor->settings = json_decode($vendor->settings, true);
        $vendor->social_links = json_decode($vendor->social_links, true);
        return response()->json($vendor);
        return $this->success($vendor, 'Profile retrieved successfully');
    }
    
    public function update(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return $this->error('Vendor not found', 404);
        }
        
        $validated = $request->validate([
            'business_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:100',
            'logo' => 'nullable|string', // Allow string (URL)
            'cover_image' => 'nullable|string', // Allow string (URL)
            'social_links' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);
        
        // Handle JSON fields
        if (isset($validated['social_links'])) {
            $validated['social_links'] = json_encode($validated['social_links']);
        }
        if (isset($validated['settings'])) {
            $validated['settings'] = json_encode($validated['settings']);
        }
        
        $vendor->update($validated);
        
        // Return with decoded JSON for response
        $vendor->settings = json_decode($vendor->settings, true);
        $vendor->social_links = json_decode($vendor->social_links, true);
        
        return $this->success($vendor, 'Profile updated successfully');
    }
    
    public function uploadImage(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        
        $data = [];
        
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($vendor->logo) {
                $oldPath = str_replace('/storage/', '', $vendor->logo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('logo')->store('vendor-logos', 'public');
            $data['logo'] = Storage::url($path);
        }
        
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($vendor->cover_image) {
                $oldPath = str_replace('/storage/', '', $vendor->cover_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('cover_image')->store('vendor-covers', 'public');
            $data['cover_image'] = Storage::url($path);
        }
        
        if (empty($data)) {
            return $this->error('No file uploaded', 422);
        }
        
        $vendor->update($data);
        
        return $this->success($data, 'Image uploaded successfully');
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