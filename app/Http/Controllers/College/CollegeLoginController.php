<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CollegeLoginController extends Controller
{
    public function CollegeLogin()
    {
        return view('college.login');
    }

    public function doCollegeLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid email or password')
                ->with('login_type', 'college')
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (auth()->guard('college')->attempt($credentials)) {
            return redirect()->route('college.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password') ->with('login_type', 'college') // ✅ THIS WAS MISSING
    ->withInput();
    }

    //     public function logout(Request $request)
    // {
    //     Auth::guard('college')->logout(); 

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect('/collegelogin')->with('success', 'Logged out successfully');
    // }
    public function logout(Request $request)
    {
        Auth::guard('college')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
