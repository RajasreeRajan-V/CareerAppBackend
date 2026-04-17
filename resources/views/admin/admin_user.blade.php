@extends('layouts.app')

@section('title', 'Registered Users')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a1a1a;">Registered Users</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Manage and view all registered users</p>
    </div>
    <span class="badge rounded-pill px-3 py-2" style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; font-size:13px; font-weight:500;">
        <i class="fa-solid fa-users me-1"></i> {{ $users->total() }} Total Users
    </span>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#94a3b8; letter-spacing:.05em;">Total Users</p>
                <h3 class="fw-bold mb-0" style="color:#1e293b;">{{ $users->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#94a3b8; letter-spacing:.05em;">With Children</p>
                <h3 class="fw-bold mb-0" style="color:#1e293b;">
                    {{ $users->getCollection()->filter(fn($u) => $u->children->count() > 0)->count() }}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#94a3b8; letter-spacing:.05em;">Verified Emails</p>
                <h3 class="fw-bold mb-0" style="color:#1e293b;">
                    {{ $users->getCollection()->filter(fn($u) => $u->email_verified_at)->count() }}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <p class="text-uppercase fw-semibold mb-1" style="font-size:11px; color:#94a3b8; letter-spacing:.05em;">New This Month</p>
                <h3 class="fw-bold mb-0" style="color:#1e293b;">
                    {{ $users->getCollection()->filter(fn($u) => $u->created_at->isCurrentMonth())->count() }}
                </h3>
            </div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.userManagement.index') }}">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-color:#e2e8f0;">
                            <i class="fa-solid fa-magnifying-glass text-muted" style="font-size:13px;"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control border-start-0 ps-0"
                            placeholder="Search by name, email or phone..."
                            style="font-size:14px; border-color:#e2e8f0;">
                    </div>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" style="font-size:14px; border-radius:8px;">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'role', 'verified']))
                        <a href="{{ route('admin.userManagement.index') }}" class="btn btn-outline-secondary" style="font-size:14px; border-radius:8px;">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

    @if($users->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-users fa-3x mb-3" style="color:#cbd5e1;"></i>
            <p class="mb-0" style="font-size:15px;">No users found.</p>
        </div>

    @else
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:14px;">
                <thead style="background:#f8fafc; border-bottom:1px solid #e2e8f0;">
                    <tr>
                        <th class="ps-4" style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">#</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">User</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">Phone</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">Role</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">Education</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">Children</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">Verified</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">Joined</th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    @php
                        $avatarColors = [
                            ['bg' => '#EEEDFE', 'text' => '#3C3489'],
                            ['bg' => '#FAEEDA', 'text' => '#633806'],
                            ['bg' => '#EAF3DE', 'text' => '#27500A'],
                            ['bg' => '#E6F1FB', 'text' => '#0C447C'],
                        ];
                        $color = $avatarColors[$loop->index % 4];
                        $initials = strtoupper(substr($user->name, 0, 1))
                            . strtoupper(substr(strstr($user->name, ' ') ?: $user->name, 1, 1));
                    @endphp
                    <tr>
                        {{-- # --}}
                        <td class="ps-4" style="color:#94a3b8; font-size:13px; width:40px;">
                            {{ $users->firstItem() + $index }}
                        </td>

                        {{-- User --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:{{ $color['bg'] }};color:{{ $color['text'] }};font-size:12px;font-weight:600;">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="color:#1e293b; white-space:nowrap;">{{ $user->name }}</div>
                                    <div style="font-size:12px; color:#94a3b8;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Phone --}}
                        <td style="color:#64748b;">{{ $user->phone ?? '—' }}</td>

                        {{-- Role --}}
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge rounded-pill px-3 py-1" style="background:#EEEDFE;color:#3C3489;font-size:12px;font-weight:600;">
                                    <i class="fa-solid fa-shield-halved me-1" style="font-size:10px;"></i>Admin
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-1" style="background:#E1F5EE;color:#0F6E56;font-size:12px;font-weight:600;">
                                    <i class="fa-solid fa-user me-1" style="font-size:10px;"></i>User
                                </span>
                            @endif
                        </td>

                        {{-- Education --}}
                        <td style="color:#64748b; max-width:150px;">{{ $user->current_education ?? '—' }}</td>

                        {{-- Children --}}
                        <td>
                            @if($user->children->count() > 0)
                                <button class="btn btn-sm border"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#children-{{ $user->id }}"
                                    aria-expanded="false"
                                    style="font-size:12px;color:#475569;border-color:#e2e8f0 !important;background:#f8fafc;border-radius:8px;padding:4px 10px;">
                                    <i class="fa-solid fa-children me-1" style="font-size:11px;"></i>
                                    {{ $user->children->count() }} {{ Str::plural('child', $user->children->count()) }}
                                    <i class="fa-solid fa-chevron-down ms-1" style="font-size:9px;"></i>
                                </button>
                                <div class="collapse mt-2" id="children-{{ $user->id }}">
                                    @foreach($user->children as $child)
                                        <div class="mb-1 px-2 py-1 rounded" style="background:#f8fafc;border-left:3px solid #cbd5e1;">
                                            <div class="fw-semibold" style="font-size:12px;color:#334155;">{{ $child->name }}</div>
                                            <div style="font-size:11px;color:#94a3b8;">{{ $child->education_level }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#cbd5e1;font-size:13px;">None</span>
                            @endif
                        </td>

                        {{-- Verified --}}
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge rounded-pill px-3 py-1" style="background:#EAF3DE;color:#3B6D11;font-size:12px;">
                                    <i class="fa-solid fa-circle-check me-1" style="font-size:10px;"></i>Verified
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-1" style="background:#FAEEDA;color:#854F0B;font-size:12px;">
                                    <i class="fa-solid fa-clock me-1" style="font-size:10px;"></i>Pending
                                </span>
                            @endif
                        </td>

                        {{-- Joined --}}
                        <td style="color:#64748b;font-size:13px;white-space:nowrap;"
                            title="{{ $user->created_at->format('d M Y, h:i A') }}">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ route('admin.userManagement.show', $user->id) }}"
                                    class="action-icon-btn text-primary"
                                    title="View">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.userManagement.edit', $user->id) }}"
                                    class="action-icon-btn text-warning"
                                    title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.userManagement.destroy', $user->id) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this user? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon-btn text-danger border-0" title="Delete">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-4 py-3"
            style="border-top:1px solid #f1f5f9;">
            <p class="mb-0 text-muted" style="font-size:13px;">
                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
            </p>
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    .table > :not(caption) > * > * {
        padding: 12px 14px;
        vertical-align: middle;
    }
    .table tbody tr:hover {
        background-color: #fafafa;
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Action icon buttons */
    .action-icon-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        border: 1px solid #e2e8f0;
        background: transparent;
        cursor: pointer;
        text-decoration: none;
        font-size: 13px;
        transition: background 0.15s, border-color 0.15s;
    }
    .action-icon-btn.text-primary:hover  { background: #EFF6FF; border-color: #BFDBFE; }
    .action-icon-btn.text-warning:hover  { background: #FFFBEB; border-color: #FDE68A; }
    .action-icon-btn.text-danger:hover   { background: #FEF2F2; border-color: #FECACA; }
</style>
@endpush