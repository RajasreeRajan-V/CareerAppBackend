<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Mail\CollegeRenewalReminderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CollegeLoginController extends Controller
{
    // ── LOGIN PAGE ────────────────────────────────
    public function CollegeLogin()
    {
        return view('college.login');
    }

    // ── LOGIN PROCESS ─────────────────────────────
    public function doCollegeLogin(Request $request)
    {
        // ── 1. VALIDATE ───────────────────────────
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('login_type', 'college');
        }

        // ── 2. ATTEMPT LOGIN ──────────────────────
        $credentials = $request->only('email', 'password');

        if (!Auth::guard('college')->attempt($credentials)) {
            return redirect()->back()
                ->with('error', 'Invalid email or password')
                ->withInput()
                ->with('login_type', 'college');
        }

        // ── 3. GET AUTHENTICATED COLLEGE ──────────
        $college = Auth::guard('college')->user();

        // Use startOfDay on both sides so comparison is purely date-based
        $createdAt  = Carbon::parse($college->created_at)->startOfDay();
        $expiry     = $createdAt->copy()->addYear()->startOfDay();
        $today      = Carbon::today()->startOfDay();

        // CarbonInterval days as absolute integer difference
        $daysLeft   = (int) $today->diffInDays($expiry, false);
        $expiryDate = $expiry->format('d M Y');

        // ── FULL VISIBILITY LOG ───────────────────
        Log::emergency('=== COLLEGE LOGIN EXPIRY DEBUG ===', [
            'college_id'  => $college->id,
            'college_name'=> $college->name,
            'email'       => $college->email,
            'created_at'  => $college->created_at,
            'parsed_created' => $createdAt->toDateTimeString(),
            'expiry'      => $expiry->toDateTimeString(),
            'today'       => $today->toDateTimeString(),
            'days_left'   => $daysLeft,
            'will_send_mail' => ($daysLeft >= 1 && $daysLeft <= 2) ? 'YES ✓' : 'NO ✗',
        ]);

        // ── 4. EXPIRED — BLOCK LOGIN ───────────────
        if ($daysLeft <= 0) {
            Auth::guard('college')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::warning('College login blocked - subscription expired', [
                'college_id' => $college->id,
                'email'      => $college->email,
                'expired_on' => $expiryDate,
            ]);

            return redirect()->route('renew', ['college' => $college->id])
                ->with(
                    'warning',
                    "Your subscription expired on {$expiryDate}. Please renew to continue."
                )
                ->with('login_type', 'college');
        }

        // ── 5. SEND REMINDER MAIL (1–2 days before expiry) ──
        if ($daysLeft >= 1 && $daysLeft <= 2) {
            $this->sendRenewalReminder($college, $daysLeft, $expiryDate);
        }

        // ── 6. REGENERATE SESSION ──────────────────
        $request->session()->regenerate();

        // ── 7. LOGIN SUCCESS ───────────────────────
        return redirect()->route('college.dashboard');
    }

    // ── RENEWAL REMINDER MAIL ─────────────────────────────────
    private function sendRenewalReminder(
        $college,
        int $daysLeft,
        string $expiryDate
    ): void {
        // Log BEFORE attempt so we know it was called
        Log::emergency('=== SENDING RENEWAL REMINDER MAIL ===', [
            'college_id' => $college->id,
            'email'      => $college->email,
            'days_left'  => $daysLeft,
            'expiry'     => $expiryDate,
        ]);

        try {
            // Verify mailable can be constructed before sending
            $mailable = new CollegeRenewalReminderMail($college, $daysLeft, $expiryDate);

            Log::emergency('Mailable constructed OK, sending now...');

            Mail::to($college->email)->send($mailable);

            Log::emergency('=== MAIL SENT SUCCESSFULLY ===', [
                'college_id' => $college->id,
                'email'      => $college->email,
            ]);

        } catch (\Throwable $e) {
            // \Throwable catches BOTH Exception AND Error (e.g. TypeError, Fatal)
            Log::emergency('=== MAIL SEND FAILED ===', [
                'college_id' => $college->id,
                'email'      => $college->email,
                'error'      => $e->getMessage(),
                'class'      => get_class($e),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'trace'      => $e->getTraceAsString(),
            ]);
        }
    }

    // ── LOGOUT ─────────────────────────────────
    public function logout(Request $request)
    {
        Auth::guard('college')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logged out successfully');
    }
}