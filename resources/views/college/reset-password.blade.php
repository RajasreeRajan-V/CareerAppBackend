{{-- resources/views/college/auth/reset-password.blade.php --}}
@extends('college.layout.guest')
@section('title', 'Reset Password')

@section('topbar', 'hide')
@section('footer', 'hide')

@push('styles')
<style>
    #guestContent {
        background: linear-gradient(160deg, #306060, #1e4242);
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
    }

    .rp-card {
        background: #ffffff;
        width: 100%;
        max-width: 420px;
        padding: 36px 35px 32px;
        border-radius: 16px;
        box-shadow:
            0 25px 50px rgba(0,0,0,.2),
            0 10px 20px rgba(0,0,0,.12);
    }

    .rp-logo-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 24px;
    }

    .rp-logo-ring {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
    }

    .rp-logo-ring:hover { transform: scale(1.04); }

    .rp-logo-ring::before {
        content: "";
        position: absolute;
        inset: 6px;
        border-radius: 50%;
        border: 2.5px solid rgba(48,96,96,.3);
    }

    .rp-logo-ring::after {
        content: "";
        position: absolute;
        inset: 2px;
        border-radius: 50%;
        border: 1.5px solid rgba(79,163,163,.75);
    }

    .rp-logo-inner {
        width: 82px;
        height: 82px;
        background: rgba(48,96,96,.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .rp-logo-inner i {
        font-size: 36px;
        color: #306060;
    }

    .rp-title {
        text-align: center;
        color: #2f2f2f;
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 6px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-subtitle {
        text-align: center;
        color: #6b7280;
        font-size: 13.5px;
        margin: 0 0 26px;
        line-height: 1.55;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 18px;
        line-height: 1.4;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-alert i { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

    .rp-alert-error {
        background: #fff0f0;
        border: 1px solid #ffb3b3;
        color: #8b0000;
    }

    .rp-form-group { margin-bottom: 16px; }

    .rp-label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #2f2f2f;
        margin-bottom: 5px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-input-wrap { position: relative; }

    .rp-input-wrap > i.rp-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 13px;
        pointer-events: none;
    }

    .rp-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #9ca3af;
        font-size: 15px;
        padding: 4px;
        line-height: 1;
        display: flex;
        align-items: center;
    }

    .rp-toggle:hover { color: #4b5563; }

    .rp-input {
        width: 100%;
        padding: 11px 38px 11px 36px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
        transition: border .25s, box-shadow .25s;
        background: #fff;
        color: #2f2f2f;
        outline: none;
        box-sizing: border-box;
    }

    .rp-input:focus {
        border-color: #306060;
        box-shadow: 0 0 0 3px rgba(48,96,96,.14);
    }

    .rp-input::placeholder { color: #9ca3af; }
    .rp-input.is-invalid { border-color: #e74c3c; background: #fff8f8; }

    .rp-field-error {
        margin-top: 5px;
        font-size: 12px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 5px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-bars { display: flex; gap: 4px; margin-bottom: 4px; }

    .rp-bar {
        height: 6px;
        flex: 1;
        border-radius: 3px;
        background: #e5e7eb;
        transition: background .3s;
    }

    .rp-slabel {
        font-size: 12px;
        color: #9ca3af;
        height: 16px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-match {
        font-size: 12px;
        height: 16px;
        margin-top: 5px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-btn {
        width: 100%;
        margin-top: 8px;
        background: #306060;
        color: #ffffff;
        border: none;
        padding: 13px;
        font-size: 15px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background .25s, transform .15s;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-btn:hover { background: #254f4f; transform: translateY(-1px); }
    .rp-btn:active { transform: translateY(0); }

    .rp-footer-text {
        margin-top: 20px;
        text-align: center;
        font-size: 13px;
        color: #6b7280;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .rp-footer-text a {
        color: #306060;
        text-decoration: none;
        font-weight: 600;
    }

    .rp-footer-text a:hover { text-decoration: underline; }

    @media (max-width: 480px) {
        .rp-card { padding: 28px 22px 26px; }
    }
</style>
@endpush

@section('content')
<div class="rp-card">

    <div class="rp-logo-wrapper">
        <div class="rp-logo-ring">
            <div class="rp-logo-inner">
                <i class="fa-solid fa-lock"></i>
            </div>
        </div>
    </div>

    <h2 class="rp-title">Set a new password</h2>
    <p class="rp-subtitle">
        Must be at least 8 characters with an uppercase<br>
        letter, a number, and a special character.
    </p>

    @if (session('error'))
        <div class="rp-alert rp-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('college.password.update') }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        {{-- New Password --}}
        <div class="rp-form-group">
            <label for="password" class="rp-label">
                New Password <span style="color:#e74c3c">*</span>
            </label>
            <div class="rp-input-wrap">
                <i class="fa-solid fa-key rp-icon"></i>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Min 8 chars, A–Z, 0–9, @$!…"
                    autocomplete="new-password"
                    class="rp-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                />
                <button type="button" class="rp-toggle" onclick="rpToggle('password', this)" tabindex="-1">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            @error('password')
                <p class="rp-field-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Strength meter --}}
        <div class="rp-form-group">
            <div class="rp-bars">
                <div class="rp-bar" id="b1"></div>
                <div class="rp-bar" id="b2"></div>
                <div class="rp-bar" id="b3"></div>
                <div class="rp-bar" id="b4"></div>
            </div>
            <div class="rp-slabel" id="slabel"></div>
        </div>

        {{-- Confirm Password --}}
        <div class="rp-form-group" style="margin-bottom: 24px;">
            <label for="password_confirmation" class="rp-label">
                Confirm New Password <span style="color:#e74c3c">*</span>
            </label>
            <div class="rp-input-wrap">
                <i class="fa-solid fa-key rp-icon"></i>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Re-enter your new password"
                    autocomplete="new-password"
                    class="rp-input {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                />
                <button type="button" class="rp-toggle" onclick="rpToggle('password_confirmation', this)" tabindex="-1">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <p class="rp-field-error">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
            @enderror
            <div class="rp-match" id="mlabel"></div>
        </div>

        <button type="submit" class="rp-btn">
            <i class="fa-solid fa-rotate-right"></i>
            Reset Password
        </button>
    </form>

    <p class="rp-footer-text">
        <a href="{{ route('login') }}">
            <i class="fa-solid fa-arrow-left" style="font-size:.75rem;"></i> Back to Login
        </a>
    </p>

</div>
@endsection

@push('scripts')
<script>
    function rpToggle(id, btn) {
        const f = document.getElementById(id);
        const icon = btn.querySelector('i');
        f.type = f.type === 'password' ? 'text' : 'password';
        icon.className = f.type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';
    }

    const pw      = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const bars    = [1,2,3,4].map(n => document.getElementById('b' + n));
    const slabel  = document.getElementById('slabel');
    const mlabel  = document.getElementById('mlabel');

    const barColors  = ['#ef4444','#f97316','#eab308','#22c55e'];
    const textColors = ['#ef4444','#f97316','#ca8a04','#16a34a'];
    const words      = ['Weak','Fair','Good','Strong'];

    pw.addEventListener('input', () => {
        const v = pw.value;
        let score = 0;
        if (v.length >= 8)                    score++;
        if (/[A-Z]/.test(v))                  score++;
        if (/[0-9]/.test(v))                  score++;
        if (/[@$!%*?&#^()_\-+=]/.test(v))     score++;

        bars.forEach((b, i) => {
            b.style.background = i < score ? barColors[score - 1] : '#e5e7eb';
        });
        slabel.textContent = score > 0 ? words[score - 1] : '';
        slabel.style.color = score > 0 ? textColors[score - 1] : '#9ca3af';
        checkMatch();
    });

    confirm.addEventListener('input', checkMatch);

    function checkMatch() {
        if (!confirm.value) { mlabel.textContent = ''; return; }
        const ok = pw.value === confirm.value;
        mlabel.textContent = ok ? '✓ Passwords match' : '✗ Passwords do not match';
        mlabel.style.color = ok ? '#16a34a' : '#dc2626';
    }
</script>
@endpush