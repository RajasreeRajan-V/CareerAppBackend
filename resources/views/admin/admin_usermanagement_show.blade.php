@extends('layouts.app')

@section('title', 'User Details')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a1a1a;">User Details</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Viewing profile of {{ $user->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.userManagement.edit', $user->id) }}"
            class="btn btn-dark btn-sm px-3" style="border-radius:8px; font-size:13px;">
            <i class="fa-regular fa-pen-to-square me-1"></i> Edit
        </a>
        <a href="{{ route('admin.userManagement.index') }}"
            class="btn btn-outline-secondary btn-sm px-3" style="border-radius:8px; font-size:13px;">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Left: Profile Card --}}
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm text-center p-4" style="border-radius:14px;">
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

            {{-- Avatar --}}
            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                style="width:72px;height:72px;background:{{ $color['bg'] }};color:{{ $color['text'] }};font-size:24px;font-weight:700;">
                {{ $initials }}
            </div>

            <h5 class="fw-bold mb-1" style="color:#1e293b;">{{ $user->name }}</h5>
            <p class="text-muted mb-3" style="font-size:13px;">{{ $user->email }}</p>

            {{-- Role Badge --}}
            @if($user->role === 'admin')
                <span class="badge rounded-pill px-3 py-2" style="background:#EEEDFE;color:#3C3489;font-size:12px;font-weight:600;">
                    <i class="fa-solid fa-shield-halved me-1"></i> Admin
                </span>
            @else
                <span class="badge rounded-pill px-3 py-2" style="background:#E1F5EE;color:#0F6E56;font-size:12px;font-weight:600;">
                    <i class="fa-solid fa-user me-1"></i> User
                </span>
            @endif

            <hr style="border-color:#f1f5f9;" class="my-3">

            {{-- Verified Status --}}
            <div class="d-flex align-items-center justify-content-between px-1">
                <span style="font-size:13px; color:#64748b;">Email Status</span>
                @if($user->email_verified_at)
                    <span class="badge rounded-pill px-3 py-1" style="background:#EAF3DE;color:#3B6D11;font-size:12px;">
                        <i class="fa-solid fa-circle-check me-1"></i> Verified
                    </span>
                @else
                    <span class="badge rounded-pill px-3 py-1" style="background:#FAEEDA;color:#854F0B;font-size:12px;">
                        <i class="fa-solid fa-clock me-1"></i> Pending
                    </span>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-between px-1 mt-2">
                <span style="font-size:13px; color:#64748b;">Joined</span>
                <span style="font-size:13px; color:#1e293b; font-weight:500;">
                    {{ $user->created_at->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Right: Details --}}
    <div class="col-12 col-md-8 d-flex flex-column gap-4">

        {{-- Basic Info --}}
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body">
                <h6 class="fw-semibold mb-3 pb-2" style="color:#1e293b; border-bottom:1px solid #f1f5f9; font-size:13px; text-transform:uppercase; letter-spacing:.05em;">
                    <i class="fa-solid fa-circle-info me-2" style="color:#94a3b8;"></i> Basic Information
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <p class="mb-0" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em;">Full Name</p>
                        <p class="mb-0 fw-semibold" style="font-size:14px; color:#1e293b;">{{ $user->name }}</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em;">Phone</p>
                        <p class="mb-0 fw-semibold" style="font-size:14px; color:#1e293b;">{{ $user->phone ?? '—' }}</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em;">Email</p>
                        <p class="mb-0 fw-semibold" style="font-size:14px; color:#1e293b;">{{ $user->email }}</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0" style="font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em;">Education</p>
                        <p class="mb-0 fw-semibold" style="font-size:14px; color:#1e293b;">{{ $user->current_education ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Children --}}
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body">
                <h6 class="fw-semibold mb-3 pb-2" style="color:#1e293b; border-bottom:1px solid #f1f5f9; font-size:13px; text-transform:uppercase; letter-spacing:.05em;">
                    <i class="fa-solid fa-children me-2" style="color:#94a3b8;"></i>
                    Children
                    <span class="badge rounded-pill ms-1 px-2" style="background:#f1f5f9; color:#475569; font-size:11px;">
                        {{ $user->children->count() }}
                    </span>
                </h6>

                @if($user->children->count() > 0)
                    <div class="d-flex flex-column gap-2">
                        @foreach($user->children as $child)
                            <div class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3"
                                style="background:#f8fafc; border-left:3px solid #cbd5e1;">
                                <div>
                                    <div class="fw-semibold" style="font-size:13px; color:#334155;">{{ $child->name }}</div>
                                    <div style="font-size:12px; color:#94a3b8;">{{ $child->education_level ?? '—' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0" style="font-size:13px;">No children registered.</p>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection