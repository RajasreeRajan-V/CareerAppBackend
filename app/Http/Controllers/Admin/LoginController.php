<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\College;

class LoginController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);


       if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid email or password')
                ->with('login_type', 'admin')
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (auth()->guard('admin')->attempt($credentials)) {
        return redirect()->route('admin.dashboard'); 
    }

        return redirect()->back()->with('error', 'Invalid email or password') ->with('login_type', 'admin')
    ->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    
     public function privacyPolicy()
    {
        return view('admin.privacy_policy');
    }
    
   public function renew($college)
{
    $college = College::findOrFail($college);
    return view('college.renew', compact('college'));
}

public function renewSubmit(Request $request, $college)
{
    $request->validate([
        'college_id'    => 'required|exists:colleges,id',
        'plan'          => 'required|in:6months,1year,2years',
        'contact_name'  => 'required|string|max:255',
        'contact_phone' => 'required|digits:10',
        'notes'         => 'nullable|string|max:1000',
    ]);

    return redirect()->route('renew', ['college' => $college])
        ->with('success', 'Renewal request submitted! Admin will review within 1–2 business days.');
}
}
