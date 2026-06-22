@extends('layouts.app')

@section('title', 'Registered Users')

@section('content')

{{-- Page Header --}}
{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a1a1a;">Registered Users</h4>
        <p class="text-muted mb-0" style="font-size:13px;">Manage and view all registered users</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge rounded-pill px-3 py-2"
            style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; font-size:13px; font-weight:500;">
            <i class="fa-solid fa-users me-1"></i> {{ $users->total() }} Total Users
        </span>
        <button onclick="downloadPDF()" class="btn btn-sm d-flex align-items-center gap-1"
            style="background:#1e293b; color:#fff; border-radius:8px; font-size:13px; font-weight:500; padding:7px 14px;">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <p class="text-uppercase fw-semibold mb-1"
                    style="font-size:11px; color:#94a3b8; letter-spacing:.05em;">Total Users</p>
                <h3 class="fw-bold mb-0" style="color:#1e293b;">{{ $users->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <p class="text-uppercase fw-semibold mb-1"
                    style="font-size:11px; color:#94a3b8; letter-spacing:.05em;">New This Month</p>
                <h3 class="fw-bold mb-0" style="color:#1e293b;">
                    {{ $users->getCollection()->filter(fn($u) => $u->created_at?->isCurrentMonth())->count() }}
                </h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body">
                <p class="text-uppercase fw-semibold mb-1"
                    style="font-size:11px; color:#94a3b8; letter-spacing:.05em;">This Page</p>
                <h3 class="fw-bold mb-0" style="color:#1e293b;">{{ $users->count() }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Search --}}
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
                    <button type="submit" class="btn btn-dark flex-grow-1"
                        style="font-size:14px; border-radius:8px;">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.userManagement.index') }}"
                            class="btn btn-outline-secondary"
                            style="font-size:14px; border-radius:8px;">
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
                        <th class="ps-4"
                            style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">
                            #
                        </th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">
                            User
                        </th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">
                            Phone
                        </th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">
                            Joined
                        </th>
                        <th style="font-size:11px; color:#94a3b8; letter-spacing:.05em; font-weight:600; text-transform:uppercase; padding:12px 14px;">
                            Actions
                        </th>
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
                        $color    = $avatarColors[$loop->index % 4];
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
                                    <div class="fw-semibold" style="color:#1e293b; white-space:nowrap;">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:12px; color:#94a3b8;">
                                        {{ $user->email ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Phone --}}
                        <td style="color:#64748b;">{{ $user->phone ?? '—' }}</td>

                        {{-- Joined --}}
                        <td style="color:#64748b; font-size:13px; white-space:nowrap;"
                            title="{{ $user->created_at?->format('d M Y, h:i A') }}">
                            {{ $user->created_at?->format('d M Y') ?? '—' }}
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <!--<a href="{{ route('admin.userManagement.show', $user->id) }}"-->
                                <!--    class="action-icon-btn text-primary" title="View">-->
                                <!--    <i class="fa-regular fa-eye"></i>-->
                                <!--</a>-->
                                <!--<a href="{{ route('admin.userManagement.edit', $user->id) }}"-->
                                <!--    class="action-icon-btn text-warning" title="Edit">-->
                                <!--    <i class="fa-regular fa-pen-to-square"></i>-->
                                <!--</a>-->
                                <form method="POST"
                                    action="{{ route('admin.userManagement.destroy', $user->id) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this user? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon-btn text-danger border-0"
                                        title="Delete">
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
                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }}
                of {{ $users->total() }} users
            </p>
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>
{{-- Hidden PDF Table (all users, no pagination) --}}
<div id="pdf-content" style="display:none;">
    <h2 style="margin-bottom:4px;">Registered Users</h2>
    <p style="font-size:12px; color:#64748b; margin-bottom:12px;">
        Generated on {{ now()->format('d M Y, h:i A') }} &nbsp;|&nbsp; Total: {{ $users->total() }} users
    </p>
    <table id="pdf-table" border="1" cellpadding="7" cellspacing="0"
        style="width:100%; border-collapse:collapse; font-size:12px;">
        <thead style="background:#f1f5f9;">
            <tr>
                <th style="text-align:left;">#</th>
                <th style="text-align:left;">Name</th>
                <th style="text-align:left;">Email</th>
                <th style="text-align:left;">Phone</th>
                <th style="text-align:left;">Joined</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allUsers as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email ?? '—' }}</td>
                <td>{{ $user->phone ?? '—' }}</td>
                <td>{{ $user->created_at?->format('d M Y') ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
    .action-icon-btn.text-primary:hover { background: #EFF6FF; border-color: #BFDBFE; }
    .action-icon-btn.text-warning:hover { background: #FFFBEB; border-color: #FDE68A; }
    .action-icon-btn.text-danger:hover  { background: #FEF2F2; border-color: #FECACA; }
    @media print {
    body * { visibility: hidden; }
    #pdf-content, #pdf-content * { visibility: visible; }
    #pdf-content {
        display: block !important;
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        padding: 30px;
    }
}
</style>
@endpush
@push('scripts')
<script>
function downloadPDF() {
    const printContents = document.getElementById('pdf-content').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;

    location.reload(); // reload to restore page properly
}
</script>
@endpush