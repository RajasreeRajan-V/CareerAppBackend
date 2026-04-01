<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get user profile data
     */
    public function getProfile(Request $request)
    {
        try {
            $user = $request->authUser; // FIXED

            $profileData = [
                'user_id' => (string) $user->id,
                'role' => $user->role,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ];

            // Add role-specific data
            if ($user->role === 'Student') {
                $profileData['current_education'] = $user->current_education;
            } elseif ($user->role === 'Parent') {
                $children = $user->children()->get()->map(function ($child) {
                    return [
                        'id' => (string) $child->id,
                        'name' => $child->name,
                        'education_level' => $child->education_level,
                    ];
                });

                $profileData['children'] = $children;
            }

            return response()->json([
                'status' => "1",
                'status_code' => "200",
                'data' => $profileData,
                'message' => 'Profile retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Profile fetch error: ' . $e->getMessage());

            return response()->json([
                'status' => "0",
                'status_code' => "500",
                'data' => (object)[],
                'message' => 'Failed to retrieve profile'
            ], 500);
        }
    }
    
    public function updateProfile(Request $request)
{
    try {
        $user = $request->authUser;

        $validator = \Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|regex:/^[6-9][0-9]{9}$/',
            'password' => 'nullable|min:8',
            'current_education' => 'nullable|string|max:255',

            'children' => 'nullable|array',
            'children.*.name' => 'required_with:children|string|max:255',
            'children.*.education_level' => 'required_with:children|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'status_code' => "422",
                'data' => (object)[],
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Update basic fields
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        if ($user->role === 'Student' && $request->filled('current_education')) {
            $user->current_education = $request->current_education;
        }

        $user->save();

        // Parent → Update children
        if ($user->role === 'Parent' && $request->filled('children')) {
            // Delete old children
            $user->children()->delete();

            // Add new children
            foreach ($request->children as $child) {
                $user->children()->create($child);
            }
        }

        return response()->json([
            'status' => "1",
            'status_code' => "200",
            'data' => [
                'user_id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
            'message' => 'Profile updated successfully'
        ], 200);

    } catch (\Exception $e) {
        Log::error('Profile update error: ' . $e->getMessage());

        return response()->json([
            'status' => "0",
            'status_code' => "500",
            'data' => (object)[],
            'message' => 'Failed to update profile'
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
