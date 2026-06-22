@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('admin.userManagement.index') }}"
                class="text-muted text-decoration-none d-flex align-items-center gap-1"
                style="font-size:13px;">
                <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i> Users
            </a>
            <span style="color:#cbd5e1; font-size:13px;">/</span>
            <span style="font-size:13px; color:#475569;">Edit</span>
        </div>
        <h4 class="fw-bold mb-0" style="color:#1a1a1a;">Edit User</h4>
    </div>

    {{-- Avatar + name badge --}}
    @php
        $avatarColors = [
            ['bg' => '#EEEDFE', 'text' => '#3C3489'],
            ['bg' => '#FAEEDA', 'text' => '#633806'],
            ['bg' => '#EAF3DE', 'text' => '#27500A'],
            ['bg' => '#E6F1FB', 'text' => '#0C447C'],
        ];
        $color    = $avatarColors[$user->id % 4];
        $initials = strtoupper(substr($user->name, 0, 1))
                  . strtoupper(substr(strstr($user->name, ' ') ?: $user->name, 1, 1));
    @endphp
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
        style="background:#f8fafc; border:1px solid #e2e8f0;">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
            style="width:32px;height:32px;background:{{ $color['bg'] }};color:{{ $color['text'] }};font-size:11px;font-weight:700;">
            {{ $initials }}
        </div>
        <div>
            <div class="fw-semibold" style="font-size:13px; color:#1e293b; line-height:1.2;">{{ $user->name }}</div>
            <div style="font-size:11px; color:#94a3b8;">{{ $user->email }}</div>
        </div>
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert border-0 mb-4 d-flex align-items-center gap-2"
        style="background:#EAF3DE; color:#27500A; border-radius:10px; font-size:14px;" role="alert">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert border-0 mb-4"
        style="background:#FEF2F2; color:#991B1B; border-radius:10px; font-size:14px;" role="alert">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <strong>Please fix the following errors:</strong>
        </div>
        <ul class="mb-0 ps-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.userManagement.update', $user->id) }}">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-12 col-lg-8">

            {{-- Basic Information --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-4 d-flex align-items-center gap-2"
                        style="color:#1e293b; font-size:14px;">
                        <span class="d-flex align-items-center justify-content-center rounded-2"
                            style="width:28px;height:28px;background:#f1f5f9;">
                            <i class="fa-solid fa-user" style="font-size:12px;color:#475569;"></i>
                        </span>
                        Basic Information
                    </h6>

                    <div class="row g-3">
                        {{-- Full Name --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter full name"
                                style="font-size:14px; border-color:#e2e8f0; border-radius:8px;">
                            @error('name')
                                <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter email address"
                                style="font-size:14px; border-color:#e2e8f0; border-radius:8px;">
                            @error('email')
                                <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                                Phone Number
                            </label>
                            <input type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter phone number"
                                style="font-size:14px; border-color:#e2e8f0; border-radius:8px;">
                            @error('phone')
                                <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Current Education --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                                Current Education
                            </label>
                            <input type="text"
                                name="current_education"
                                value="{{ old('current_education', $user->current_education) }}"
                                class="form-control @error('current_education') is-invalid @enderror"
                                placeholder="e.g. Bachelor's in Computer Science"
                                style="font-size:14px; border-color:#e2e8f0; border-radius:8px;">
                            @error('current_education')
                                <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-1 d-flex align-items-center gap-2"
                        style="color:#1e293b; font-size:14px;">
                        <span class="d-flex align-items-center justify-content-center rounded-2"
                            style="width:28px;height:28px;background:#f1f5f9;">
                            <i class="fa-solid fa-lock" style="font-size:12px;color:#475569;"></i>
                        </span>
                        Change Password
                    </h6>
                    <p class="text-muted mb-4" style="font-size:12px; padding-left:36px;">
                        Leave both fields blank to keep the current password.
                    </p>

                    <div class="row g-3">
                        {{-- New Password --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                                New Password
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Min. 8 characters"
                                    style="font-size:14px; border-color:#e2e8f0; border-radius:8px 0 0 8px;">
                                <button type="button"
                                    class="btn btn-outline-secondary border-start-0 toggle-password"
                                    data-target="password"
                                    style="border-color:#e2e8f0; border-radius:0 8px 8px 0;">
                                    <i class="fa-regular fa-eye" style="font-size:13px;"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                                Confirm New Password
                            </label>
                            <div class="input-group">
                                <input type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Repeat new password"
                                    style="font-size:14px; border-color:#e2e8f0; border-radius:8px 0 0 8px;">
                                <button type="button"
                                    class="btn btn-outline-secondary border-start-0 toggle-password"
                                    data-target="password_confirmation"
                                    style="border-color:#e2e8f0; border-radius:0 8px 8px 0;">
                                    <i class="fa-regular fa-eye" style="font-size:13px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Children --}}
            @if($user->children->count() > 0)
            <div class="card border-0 shadow-sm" style="border-radius:12px;">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-4 d-flex align-items-center gap-2"
                        style="color:#1e293b; font-size:14px;">
                        <span class="d-flex align-items-center justify-content-center rounded-2"
                            style="width:28px;height:28px;background:#f1f5f9;">
                            <i class="fa-solid fa-children" style="font-size:12px;color:#475569;"></i>
                        </span>
                        Children
                        <span class="badge rounded-pill ms-1"
                            style="background:#f1f5f9;color:#475569;font-size:11px;font-weight:500;">
                            {{ $user->children->count() }}
                        </span>
                    </h6>

                    <div class="d-flex flex-column gap-2">
                        @foreach($user->children as $child)
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3"
                            style="background:#f8fafc; border:1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:32px;height:32px;background:#E6F1FB;color:#0C447C;font-size:11px;font-weight:700;">
                                    {{ strtoupper(substr($child->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:13px; color:#1e293b;">{{ $child->name }}</div>
                                    <div style="font-size:12px; color:#94a3b8;">{{ $child->education_level ?? '—' }}</div>
                                </div>
                            </div>
                            <span class="badge rounded-pill px-2 py-1"
                                style="background:#EAF3DE;color:#27500A;font-size:11px;">
                                Active
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-12 col-lg-4">

            {{-- Account Settings --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-4 d-flex align-items-center gap-2"
                        style="color:#1e293b; font-size:14px;">
                        <span class="d-flex align-items-center justify-content-center rounded-2"
                            style="width:28px;height:28px;background:#f1f5f9;">
                            <i class="fa-solid fa-sliders" style="font-size:12px;color:#475569;"></i>
                        </span>
                        Account Settings
                    </h6>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                            Role <span class="text-danger">*</span>
                        </label>
                        <select name="role"
                            class="form-select @error('role') is-invalid @enderror"
                            style="font-size:14px; border-color:#e2e8f0; border-radius:8px; color:#374151;">
                            <option value="user"  @selected(old('role', $user->role) === 'user')>
                                User
                            </option>
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>
                                Admin
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email Verification --}}
                    <div class="mb-0">
                        <label class="form-label" style="font-size:13px; font-weight:500; color:#374151;">
                            Email Verification
                        </label>
                        <div class="p-3 rounded-3 d-flex align-items-center justify-content-between"
                            style="background:#f8fafc; border:1px solid #e2e8f0;">
                            @if($user->email_verified_at)
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge rounded-pill px-2 py-1"
                                            style="background:#EAF3DE;color:#3B6D11;font-size:11px;">
                                            <i class="fa-solid fa-circle-check me-1" style="font-size:9px;"></i>Verified
                                        </span>
                                    </div>
                                    <div style="font-size:11px; color:#94a3b8;">
                                        {{ $user->email_verified_at->format('d M Y, h:i A') }}
                                    </div>
                                </div>
                            @else
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge rounded-pill px-2 py-1"
                                            style="background:#FAEEDA;color:#854F0B;font-size:11px;">
                                            <i class="fa-solid fa-clock me-1" style="font-size:9px;"></i>Pending
                                        </span>
                                    </div>
                                    <div style="font-size:11px; color:#94a3b8;">Not yet verified</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Meta Info --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-4 d-flex align-items-center gap-2"
                        style="color:#1e293b; font-size:14px;">
                        <span class="d-flex align-items-center justify-content-center rounded-2"
                            style="width:28px;height:28px;background:#f1f5f9;">
                            <i class="fa-solid fa-circle-info" style="font-size:12px;color:#475569;"></i>
                        </span>
                        Account Info
                    </h6>

                    <div class="d-flex flex-column gap-3">
                        <div>
                            <p class="mb-1" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">User ID</p>
                            <p class="mb-0 fw-semibold" style="font-size:13px; color:#475569;">#{{ $user->id }}</p>
                        </div>
                        <div style="border-top:1px solid #f1f5f9; padding-top:12px;">
                            <p class="mb-1" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Joined</p>
                            <p class="mb-0 fw-semibold" style="font-size:13px; color:#475569;">
                                {{ $user->created_at->format('d M Y') }}
                            </p>
                            <p class="mb-0" style="font-size:11px; color:#94a3b8;">
                                {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div style="border-top:1px solid #f1f5f9; padding-top:12px;">
                            <p class="mb-1" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Last Updated</p>
                            <p class="mb-0 fw-semibold" style="font-size:13px; color:#475569;">
                                {{ $user->updated_at->format('d M Y') }}
                            </p>
                            <p class="mb-0" style="font-size:11px; color:#94a3b8;">
                                {{ $user->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        <div style="border-top:1px solid #f1f5f9; padding-top:12px;">
                            <p class="mb-1" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; font-weight:600;">Children</p>
                            <p class="mb-0 fw-semibold" style="font-size:13px; color:#475569;">
                                {{ $user->children->count() }} {{ Str::plural('child', $user->children->count()) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex flex-column gap-2">
                <button type="submit" class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2"
                    style="font-size:14px; border-radius:8px; padding:10px;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Changes
                </button>
                <a href="{{ route('admin.userManagement.show', $user->id) }}"
                    class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2"
                    style="font-size:14px; border-radius:8px; padding:10px; border-color:#e2e8f0; color:#475569;">
                    <i class="fa-regular fa-eye"></i>
                    View Profile
                </a>
                <a href="{{ route('admin.userManagement.index') }}"
                    class="btn w-100 d-flex align-items-center justify-content-center gap-2"
                    style="font-size:14px; border-radius:8px; padding:10px; background:#f8fafc; border:1px solid #e2e8f0; color:#475569;">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Users
                </a>

                {{-- Danger Zone --}}
                
            </div>

        </div>
    </div>

</form>
<div class="mt-2 p-3 rounded-3" style="background:#FEF2F2; border:1px solid #FECACA;">
                    <p class="mb-2 fw-semibold" style="font-size:12px; color:#991B1B;">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Danger Zone
                    </p>
                    <form method="POST" action="{{ route('admin.userManagement.destroy', $user->id) }}"
                        onsubmit="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2"
                            style="font-size:13px; border-radius:8px; background:#FEF2F2; border:1px solid #FECACA; color:#DC2626;">
                            <i class="fa-regular fa-trash-can"></i>
                            Delete This User
                        </button>
                    </form>
                </div>
@endsection

@push('styles')
<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #94a3b8 !important;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15) !important;
    }
    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }
    .btn-dark:hover {
        background: #1e293b !important;
    }
    .toggle-password:focus {
        box-shadow: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var input    = document.getElementById(targetId);
            var icon     = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endpush