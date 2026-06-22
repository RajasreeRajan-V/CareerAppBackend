<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Mail\CollegeResetPasswordMail;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CollegeForgotPasswordController extends Controller
{
    /**
     * Show the "Forgot Password" form.
     */
    public function showForgotForm()
    {
        
        return view('college.forgot-password');
    }

    /**
     * Validate the email and send a reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:colleges,email',
        ], [
            'email.exists' => 'No college account found with this email address.',
        ]);

        $college = College::where('email', $request->email)->firstOrFail();

        // Generate a secure plain token (stored hashed in the DB)
        $plainToken = Str::random(64);

        $college->update([
            'reset_token'            => Hash::make($plainToken),
            'reset_token_expires_at' => Carbon::now()->addMinutes(60),
        ]);

        // Plain token goes in the URL; hashed token is what is stored
        $resetUrl = route('college.password.reset', ['token' => $plainToken])
            . '?email=' . urlencode($college->email);

        try {
            Mail::to($college->email)->send(
                new CollegeResetPasswordMail($college->name, $resetUrl)
            );

            return back()->with(
                'success',
                'A password reset link has been sent to ' . $college->email . '. It will expire in 60 minutes.'
            );

        } catch (\Exception $e) {
            // Roll back the token so the college isn't locked in a half-state
            $college->update([
                'reset_token'            => null,
                'reset_token_expires_at' => null,
            ]);

            \Log::error('College password reset mail failed: ' . $e->getMessage());

            return back()->with(
                'error',
                'We could not send the reset email. Please try again later or contact support.'
            );
        }
    }

    /**
     * Show the "Reset Password" form (opened from the email link).
     */
    public function showResetForm(Request $request, string $token)
    {
        $email = $request->query('email');

        if (!$email) {
            abort(404);
        }

        $college = College::where('email', $email)->first();

        // No college found or no token has been issued
        if (!$college || !$college->reset_token) {
            return redirect()->route('college.password.request')
                ->with('error', 'This password reset link is invalid or has already been used.');
        }

        // Check expiry
        if ($college->reset_token_expires_at->isPast()) {
            $college->update([
                'reset_token'            => null,
                'reset_token_expires_at' => null,
            ]);

            return redirect()->route('college.password.request')
                ->with('error', 'This password reset link has expired. Please request a new one.');
        }

        return view('college.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Validate and apply the new password, then clear the token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email|exists:colleges,email',
            'password'              => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',                  // at least one uppercase
                'regex:/[0-9]/',                  // at least one digit
                'regex:/[@$!%*?&#^()_\-+=]/',     // at least one special character
            ],
            'password_confirmation' => 'required',
        ], [
            'password.min'       => 'Password must be at least 8 characters.',
            'password.regex'     => 'Password must include an uppercase letter, a number, and a special character (@$!%*?&#…).',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $college = College::where('email', $request->email)->first();

        // No token on record
        if (!$college || !$college->reset_token) {
            return back()->with('error', 'This reset link is invalid or has already been used.');
        }

        // Token expired
        if ($college->reset_token_expires_at->isPast()) {
            $college->update([
                'reset_token'            => null,
                'reset_token_expires_at' => null,
            ]);

            return redirect()->route('college.password.request')
                ->with('error', 'This reset link has expired. Please request a new one.');
        }
        
        

        // Token hash mismatch
        if (!Hash::check($request->token, $college->reset_token)) {
            return back()->with('error', 'This reset link is invalid.');
        }

        // All checks passed — update password and clear token
        $college->update([
            'password'               => Hash::make($request->password),
            'password_changed'       => true,          // marks that user set their own password
            'reset_token'            => null,           // invalidate immediately
            'reset_token_expires_at' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully. You can now log in.');
    }
}