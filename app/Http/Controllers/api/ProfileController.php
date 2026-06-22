<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Get user profile data
     */
public function getProfile(Request $request)
{
    try {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'      => "0",
                'status_code' => "422",
                'data'        => (object)[],
                'message'     => $validator->errors()->first(),
            ], 422);
        }

        $user = User::findOrFail($request->user_id);

        return response()->json([
            'status'      => "1",
            'status_code' => "200",
            'data'        => [
                'user_id' => (string) $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone,
            ],
            'message' => 'Profile retrieved successfully'
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Profile fetch error: ' . $e->getMessage());
        return response()->json([
            'status'      => "0",
            'status_code' => "500",
            'data'        => (object)[],
            'message'     => 'Failed to retrieve profile',
        ], 500);
    }
}
    
public function updateProfile(Request $request)
{
    try {
        $validator = \Validator::make($request->all(), [
            'id'    => 'required|numeric|exists:users,id',
            'name'  => 'nullable|string|max:255',
            'phone' => 'nullable|digits:10',
            'email' => 'nullable|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'      => "0",
                'status_code' => "422",
                'data'        => (object)[],
                'message'     => $validator->errors()->first(),
            ], 422);
        }
        $user = User::findOrFail($request->id);

        // Phone update (no uniqueness check)
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        // Email uniqueness check
        if ($request->filled('email') && $request->email !== $user->email) {
            $emailExists = User::where('email', $request->email)
                ->where('id', '!=', $user->id)
                ->exists();
            if ($emailExists) {
                return response()->json([
                    'status'      => "0",
                    'status_code' => "422",
                    'data'        => (object)[],
                    'message'     => 'Email is already taken by another user.',
                ], 422);
            }
            $user->email = $request->email;
        } elseif ($request->has('email') && empty($request->email)) {
            $user->email = null;
        }

        if ($request->has('name')) {
            $user->name = $request->name ?? '';
        }

        $user->save();

        return response()->json([
            'status'      => "1",
            'status_code' => "200",
            'data'        => [
                'user_id' => (string) $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone,
            ],
            'message' => 'Profile updated successfully',
        ], 200);
    } catch (\Exception $e) {
        \Log::error('Profile update error: ' . $e->getMessage());
        return response()->json([
            'status'      => "0",
            'status_code' => "500",
            'data'        => (object)[],
            'message'     => 'Failed to update profile',
        ], 500);
    }
}

public function Viewuser(Request $request)
{
    try {
        $validator = \Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'phone' => 'required|digits:10',
            'email' => 'nullable|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'      => "0",
                'status_code' => "422",
                'data'        => (object)[],
                'message'     => $validator->errors()->first(),
            ], 422);
        }

        // Prevent duplicate: find existing or create new
        $user = User::firstOrCreate(
            ['phone' => $request->phone],  // match condition
            [
                'name'  => $request->name,
                'email' => $request->email ?? null,
            ]
        );

        return response()->json([
            'status'      => "1",
            'status_code' => "200",
            'data'        => [
                'user_id' => (string) $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone,
            ],
            'message' => 'User created successfully',
        ], 200);

    } catch (\Exception $e) {
        \Log::error('User create error: ' . $e->getMessage());
        return response()->json([
            'status'      => "0",
            'status_code' => "422",
            'data'        => (object)[],
            'message'     => 'Failed to create user',
        ], 500);
    }
}


public function changePassword(Request $request)
{
    try {
        $user = $request->authUser;

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|different:current_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'status_code' => "422",
                'data' => (object)[],
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => "0",
                'status_code' => "422",
                'data' => (object)[],
                'message' => 'Current password is incorrect',
            ], 422);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => "1",
            'status_code' => "200",
            'data' => (object)[],
            'message' => 'Password changed successfully',
        ], 200);

    } catch (\Exception $e) {
        Log::error('Change password error: ' . $e->getMessage());

        return response()->json([
            'status' => "0",
            'status_code' => "500",
            'data' => (object)[],
            'message' => 'Failed to change password',
        ], 500);
    }
}

}
