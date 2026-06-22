<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('college.change-password');
    }
     public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password'              => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $college = auth()->guard('college')->user();

        // Prevent using the same password
        if (Hash::check($request->new_password, $college->password)) {
            return redirect()->back()->with('error', 'New password cannot be the same as your current password.');
        }

        $college->update([
            'password'         => Hash::make($request->new_password),
            'password_changed' => true,
        ]);

        return redirect()->route('college.dashboard')->with('success', 'Password updated successfully!');
    }
}
