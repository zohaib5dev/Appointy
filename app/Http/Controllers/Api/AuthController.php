<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['customer', 'vendor'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);


        if ($request->role === 'vendor') {

            $vendorValidator = Validator::make($request->all(), [
                'business_name' => 'required|string|max:255',
                'description' => 'required|string',
                'website' => 'nullable|url|max:255',
                'timezone' => 'required|string|max:100',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20'
            ]);

            if ($vendorValidator->fails()) {
                $user->delete();
                return response()->json(['errors' => $vendorValidator->errors()], 422);
            }
            // Save vendor
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'slug' => \Str::slug($request->business_name) . '-' . uniqid(),
                'status' => 'pending',
                'subscription_status' => 'trial',
                'stripe_account_status' => 'pending',
                'trial_ends_at' => now()->addDays(14),
                'business_name' => $request->business_name,
                'description' => $request->description,
                'phone' => $request->business_phone,
                'website' => $request->website ?? null,
                'timezone' => $request->timezone ?? 'UTC',
                'address' => $request->address ?? null,
                'city' => $request->city ?? null,
                'state' => $request->state ?? null,
                'country' => $request->country ?? null,
                'postal_code' => $request->postal_code ?? null,
                'is_verified' => false,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user->load('vendor'),
                'token' => $token,
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->status !== 'active') {
            return response()->json(['message' => 'Your account is suspended'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user->load('vendor'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('vendor'));
    }
}
