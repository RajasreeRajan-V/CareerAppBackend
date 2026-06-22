{{-- resources/views/college/auth/forgot-password.blade.php --}}
@extends('college.layout.guest')
@section('title', 'Forgot Password')

{{-- Hide the default topbar and footer on this page --}}
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

    .fp-card {
        background: #ffffff;
        width: 100%;
        max-width: 420px;
        padding: 36px 35px 32px;
        border-radius: 16px;
        box-shadow:
            0 25px 50px rgba(0,0,0,.2),
            0 10px 20px rgba(0,0,0,.12);
    }

    .fp-logo-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 24px;
    }

    .fp-logo-ring {
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

    .fp-logo-ring:hover { transform: scale(1.04); }

    .fp-logo-ring::before {
        content: "";
        position: absolute;
        inset: 6px;
        border-radius: 50%;
        border: 2.5px solid rgba(48,96,96,.3);
    }

    .fp-logo-ring::after {
        content: "";
        position: absolute;
        inset: 2px;
        border-radius: 50%;
        border: 1.5px solid rgba(79,163,163,.75);
    }

    .fp-logo-inner {
        width: 82px;
        height: 82px;
        background: rgba(48,96,96,.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fp-logo-inner i {
        font-size: 36px;
        color: #306060;
    }

    .fp-title {
        text-align: center;
        color: #2f2f2f;
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 6px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .fp-subtitle {
        text-align: center;
        color: #6b7280;
        font-size: 13.5px;
        margin: 0 0 26px;
        line-height: 1.55;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .fp-alert {
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

    .fp-alert i { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

    .fp-alert-success {
        background: #f0faf5;
        border: 1px solid #a7dfc4;
        color: #1a6644;
    }

    .fp-alert-error {
        background: #fff0f0;
        border: 1px solid #ffb3b3;
        color: #8b0000;
    }

    .fp-form-group { margin-bottom: 16px; }

    .fp-label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #2f2f2f;
        margin-bottom: 5px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .fp-input-wrap { position: relative; }

    .fp-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 13px;
        pointer-events: none;
    }

    .fp-input {
        width: 100%;
        padding: 11px 14px 11px 36px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
        transition: border .25s, box-shadow .25s;
        background: #fff;
        color: #2f2f2f;
        outline: none;
    }

    .fp-input:focus {
        border-color: #306060;
        box-shadow: 0 0 0 3px rgba(48,96,96,.14);
    }

    .fp-input::placeholder { color: #9ca3af; }
    .fp-input.is-invalid { border-color: #e74c3c; background: #fff8f8; }

    .fp-field-error {
        margin-top: 5px;
        font-size: 12px;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 5px;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .fp-btn {
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

    .fp-btn:hover {
        background: #254f4f;
        transform: translateY(-1px);
    }

    .fp-btn:active { transform: translateY(0); }

    .fp-footer-text {
        margin-top: 20px;
        text-align: center;
        font-size: 13px;
        color: #6b7280;
        font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    }

    .fp-footer-text a {
        color: #306060;
        text-decoration: none;
        font-weight: 600;
    }

    .fp-footer-text a:hover { text-decoration: underline; }

    @media (max-width: 480px) {
        .fp-card { padding: 28px 22px 26px; }
    }
</style>
@endpush

@section('content')
<div class="fp-card">

    <div class="fp-logo-wrapper">
        <div class="fp-logo-ring">
            <div class="fp-logo-inner">
                <i class="fa-solid fa-key"></i>
            </div>
        </div>
    </div>

    <h2 class="fp-title">Forgot your password?</h2>
    <p class="fp-subtitle">
        Enter your registered college email and<br>
        we'll send you a password reset link.
    </p>

    @if (session('success'))
        <div class="fp-alert fp-alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="fp-alert fp-alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('college.password.email') }}" method="POST" novalidate>
        @csrf

        <div class="fp-form-group">
            <label for="fp-email" class="fp-label">College Email</label>
            <div class="fp-input-wrap">
                <i class="fa-regular fa-envelope"></i>
                <input
                    type="email"
                    id="fp-email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="college@example.com"
                    autocomplete="email"
                    class="fp-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                >
            </div>
            @error('email')
                <p class="fp-field-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit" class="fp-btn">
            <i class="fa-regular fa-paper-plane"></i>
            Send Reset Link
        </button>
    </form>

    <p class="fp-footer-text">
        Remembered your password?
        <a href="{{ route('login') }}">
            <i class="fa-solid fa-arrow-left" style="font-size:.75rem;"></i> Back to Login
        </a>
    </p>

</div>
@endsection