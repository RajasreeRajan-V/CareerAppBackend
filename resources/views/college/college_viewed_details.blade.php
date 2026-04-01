{{-- resources/views/college/viewers.blade.php --}}
@extends('college.layouts.app')

@section('title', 'Student Viewers')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --ink:        #0f1117;
        --ink-soft:   #4b5260;
        --ink-muted:  #9aa0ad;
        --surface:    #f7f8fa;
        --card:       #ffffff;
        --accent:     #1a56db;
        --accent-bg:  #eff4ff;
        --green:      #0e9f6e;
        --green-bg:   #f0fdf4;
        --amber:      #d97706;
        --amber-bg:   #fffbeb;
        --red:        #e02424;
        --red-bg:     #fef2f2;
        --border:     #e8eaed;
        --radius:     12px;
        --shadow-sm:  0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:  0 4px 16px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.04);
    }

    body { font-family: 'DM Sans', sans-serif; background: var(--surface); color: var(--ink); }

    /* ── Page header ── */
    .page-header {
        background: var(--card);
        border-bottom: 1px solid var(--border);
        padding: 28px 0 0;
    }
    .page-header h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 1.75rem;
        letter-spacing: -.02em;
        color: var(--ink);
        margin-bottom: 4px;
    }
    .breadcrumb { font-size: 13px; color: var(--ink-muted); }
    .breadcrumb a { color: var(--accent); text-decoration: none; }

    /* ── Tabs ── */
    .tab-strip {
        display: flex;
        gap: 0;
        margin-top: 20px;
        border-bottom: none;
    }
    .tab-strip a {
        padding: 10px 20px;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--ink-muted);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: color .18s, border-color .18s;
    }
    .tab-strip a.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }
    .tab-strip a:hover:not(.active) { color: var(--ink-soft); }

    /* ── Stat row ── */
    .stat-row { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
    .stat-chip {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 160px;
        box-shadow: var(--shadow-sm);
    }
    .stat-chip .icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
    }
    .stat-chip .label { font-size: 12px; color: var(--ink-muted); margin-bottom: 2px; }
    .stat-chip .value { font-size: 20px; font-weight: 700; color: var(--ink); line-height: 1; }

    /* ── Toolbar ── */
    .toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 340px;
    }
    .search-wrap input {
        width: 100%;
        padding: 9px 14px 9px 38px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13.5px;
        font-family: 'DM Sans', sans-serif;
        background: var(--card);
        color: var(--ink);
        outline: none;
        transition: border-color .18s, box-shadow .18s;
    }
    .search-wrap input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(26,86,219,.12);
    }
    .search-wrap i {
        position: absolute;
        left: 12px; top: 50%;
        transform: translateY(-50%);
        color: var(--ink-muted);
        font-size: 15px;
    }
    .filter-select {
        padding: 9px 14px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        font-family: 'DM Sans', sans-serif;
        background: var(--card);
        color: var(--ink-soft);
        outline: none;
        cursor: pointer;
    }
    .btn-export {
        padding: 9px 18px;
        background: var(--ink);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: background .18s;
        text-decoration: none;
    }
    .btn-export:hover { background: #1e2535; color: #fff; }

    /* ── Table card ── */
    .table-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .table-card table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    .table-card thead th {
        padding: 13px 18px;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ink-muted);
        text-align: left;
        white-space: nowrap;
    }
    .table-card tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .13s;
    }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #f9fafc; }
    .table-card tbody td {
        padding: 14px 18px;
        vertical-align: middle;
        color: var(--ink-soft);
    }
    .table-card tbody td:first-child { color: var(--ink); }

    /* ── Avatar ── */
    .avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        font-size: 13px;
        color: #fff;
        flex-shrink: 0;
    }
    .student-cell { display: flex; align-items: center; gap: 12px; }
    .student-name { font-weight: 600; color: var(--ink); font-size: 14px; line-height: 1.2; }
    .student-email { font-size: 12px; color: var(--ink-muted); }

    /* ── Badges ── */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 500;
        line-height: 1.6;
    }
    .badge-new    { background: var(--green-bg);  color: var(--green);  }
    .badge-today  { background: var(--accent-bg); color: var(--accent); }
    .badge-old    { background: var(--surface);   color: var(--ink-muted); border: 1px solid var(--border); }

    /* ── Time display ── */
    .time-main { font-size: 13px; color: var(--ink); font-weight: 500; }
    .time-rel  { font-size: 11.5px; color: var(--ink-muted); }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 64px 24px;
        color: var(--ink-muted);
    }
    .empty-state .icon {
        width: 64px; height: 64px;
        background: var(--surface);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px;
        margin: 0 auto 16px;
    }
    .empty-state h5 { font-size: 16px; color: var(--ink); font-weight: 600; margin-bottom: 6px; }
    .empty-state p  { font-size: 13.5px; max-width: 300px; margin: 0 auto; }

    /* ── Pagination ── */
    .pagination-wrap {
        padding: 14px 18px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        color: var(--ink-muted);
        flex-wrap: wrap;
        gap: 10px;
    }
    .pagination-wrap .page-links { display: flex; gap: 4px; }
    .pagination-wrap .page-links a,
    .pagination-wrap .page-links span {
        padding: 6px 11px;
        border-radius: 6px;
        border: 1px solid var(--border);
        color: var(--ink-soft);
        text-decoration: none;
        font-size: 13px;
        background: var(--card);
        transition: all .15s;
    }
    .pagination-wrap .page-links .active span {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }
    .pagination-wrap .page-links a:hover { background: var(--surface); }

    /* ── Detail modal ── */
    .modal-backdrop-custom {
        position: fixed; inset: 0;
        background: rgba(15,17,23,.45);
        backdrop-filter: blur(3px);
        z-index: 1040;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-backdrop-custom.open { display: flex; }
    .modal-box {
        background: var(--card);
        border-radius: 16px;
        box-shadow: 0 24px 64px rgba(0,0,0,.18);
        width: 100%;
        max-width: 460px;
        margin: 16px;
        animation: slideUp .22s ease;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .modal-header {
        padding: 22px 24px 18px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-header h5 {
        font-family: 'DM Serif Display', serif;
        font-size: 1.2rem;
        margin: 0;
    }
    .modal-close {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: transparent;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--ink-muted);
        font-size: 18px;
        transition: background .15s;
    }
    .modal-close:hover { background: var(--surface); }
    .modal-body { padding: 24px; }
    .modal-avatar-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }
    .modal-avatar {
        width: 60px; height: 60px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        font-weight: 800;
        color: #fff;
    }
    .modal-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .info-item .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--ink-muted);
        margin-bottom: 4px;
        font-weight: 600;
    }
    .info-item .info-value {
        font-size: 14px;
        color: var(--ink);
        font-weight: 500;
        word-break: break-all;
    }
    .info-item.full { grid-column: 1 / -1; }

    /* ── Fade-in rows ── */
    @keyframes fadeRow {
        from { opacity: 0; transform: translateX(-6px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .table-card tbody tr {
        animation: fadeRow .25s ease both;
    }
    @for ($i = 1; $i <= 15; $i++)
        .table-card tbody tr:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 0.03 }}s; }
    @endfor
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div class="container-fluid px-4">
        <nav class="breadcrumb mb-1" aria-label="breadcrumb">
            <a href="{{ route('college.dashboard') }}">Dashboard</a>
            <span class="mx-2">/</span>
            <span>Student Viewers</span>
        </nav>
        <h1>Student Viewers</h1>
        <p class="text-muted mb-0" style="font-size:14px;">
            Students who have visited your college profile via the app
        </p>

        <div class="tab-strip">
            <a href="{{ route('college.dashboard') }}">Overview</a>
            <a href="{{ route('college.viewers') }}" class="active">Viewers</a>
        </div>
    </div>
</div>

<div class="container-fluid px-4 py-4">

    {{-- Stat Row --}}
    <div class="stat-row">
        <div class="stat-chip">
            <div class="icon" style="background:#ede9fe;">
                <i class="bi bi-eye" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="label">Total Views</div>
                <div class="value">{{ number_format($totalViews) }}</div>
            </div>
        </div>

        <div class="stat-chip">
            <div class="icon" style="background:#dbeafe;">
                <i class="bi bi-people" style="color:#1a56db;"></i>
            </div>
            <div>
                <div class="label">Unique Students</div>
                <div class="value">{{ number_format($uniqueViewers) }}</div>
            </div>
        </div>

        <div class="stat-chip">
            <div class="icon" style="background:#f0fdf4;">
                <i class="bi bi-calendar-check" style="color:#0e9f6e;"></i>
            </div>
            <div>
                <div class="label">Today</div>
                <div class="value">{{ number_format($todayViews) }}</div>
            </div>
        </div>

        <div class="stat-chip">
            <div class="icon" style="background:#fffbeb;">
                <i class="bi bi-graph-up-arrow" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="label">Last 7 Days</div>
                <div class="value">{{ number_format($weekViews) }}</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('college.viewers') }}" id="filterForm"
              style="display:contents;">

            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" placeholder="Search by name or email…"
                       value="{{ request('search') }}"
                       onchange="document.getElementById('filterForm').submit()">
            </div>

            <select name="period" class="filter-select"
                    onchange="document.getElementById('filterForm').submit()">
                <option value="">All Time</option>
                <option value="today"  {{ request('period')=='today'  ? 'selected' : '' }}>Today</option>
                <option value="week"   {{ request('period')=='week'   ? 'selected' : '' }}>This Week</option>
                <option value="month"  {{ request('period')=='month'  ? 'selected' : '' }}>This Month</option>
            </select>

            <select name="sort" class="filter-select"
                    onchange="document.getElementById('filterForm').submit()">
                <option value="latest" {{ request('sort')=='latest' ? 'selected' : '' }}>Latest First</option>
                <option value="oldest" {{ request('sort')=='oldest' ? 'selected' : '' }}>Oldest First</option>
            </select>

        </form>

        <a href="{{ route('college.viewers.export') }}" class="btn-export ms-auto">
            <i class="bi bi-download"></i> Export CSV
        </a>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Phone</th>
                    <th>Viewed</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($viewers as $index => $view)
                @php
                    $student  = $view->user;
                    $name     = $student->name  ?? 'Unknown';
                    $email    = $student->email ?? '—';
                    $phone    = $student->phone ?? '—';
                    $initial  = strtoupper(substr($name, 0, 1));
                    $colors   = ['#1a56db','#0e9f6e','#7c3aed','#d97706','#e02424','#0891b2'];
                    $color    = $colors[$view->user_id % count($colors)];

                    $isToday  = $view->created_at->isToday();
                    $isNew    = $view->created_at->gte(now()->subHours(24));
                @endphp
                <tr>
                    {{-- Row number --}}
                    <td style="color:var(--ink-muted); font-size:12px; width:40px;">
                        {{ $viewers->firstItem() + $index }}
                    </td>

                    {{-- Student --}}
                    <td>
                        <div class="student-cell">
                            <div class="avatar" style="background:{{ $color }};">{{ $initial }}</div>
                            <div>
                                <div class="student-name">{{ $name }}</div>
                                <div class="student-email">{{ $email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Phone --}}
                    <td>
                        @if($student && $student->phone)
                            <a href="tel:{{ $student->phone }}"
                               style="color:var(--accent); text-decoration:none; font-size:13px;">
                                {{ $student->phone }}
                            </a>
                        @else
                            <span style="color:var(--ink-muted);">—</span>
                        @endif
                    </td>

                    {{-- Viewed at --}}
                    <td>
                        <div class="time-main">{{ $view->created_at->format('d M Y') }}</div>
                        <div class="time-rel">{{ $view->created_at->format('h:i A') }} &middot; {{ $view->created_at->diffForHumans() }}</div>
                    </td>

                    {{-- Status badge --}}
                    <td>
                        @if($isToday)
                            <span class="badge badge-today">
                                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                                Today
                            </span>
                        @elseif($isNew)
                            <span class="badge badge-new">
                                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                                New
                            </span>
                        @else
                            <span class="badge badge-old">Viewed</span>
                        @endif
                    </td>

                    {{-- View detail button --}}
                    <td style="text-align:right;">
                        <button class="btn-detail"
                            style="padding:6px 14px; border-radius:7px; border:1px solid var(--border);
                                   background:transparent; font-size:12.5px; font-family:'DM Sans',sans-serif;
                                   font-weight:500; color:var(--ink-soft); cursor:pointer; transition:all .15s;"
                            onmouseover="this.style.background='var(--accent)';this.style.color='#fff';this.style.borderColor='var(--accent)';"
                            onmouseout="this.style.background='transparent';this.style.color='var(--ink-soft)';this.style.borderColor='var(--border)';"
                            onclick="openModal({
                                name:    '{{ addslashes($name) }}',
                                email:   '{{ addslashes($email) }}',
                                phone:   '{{ addslashes($phone) }}',
                                initial: '{{ $initial }}',
                                color:   '{{ $color }}',
                                viewed:  '{{ $view->created_at->format('d M Y, h:i A') }}',
                                ago:     '{{ $view->created_at->diffForHumans() }}',
                                total:   '{{ \App\Models\CollegeView::where('user_id', $view->user_id)->where('college_id', $view->college_id)->count() }}'
                            })">
                            View Details
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="icon">
                                <i class="bi bi-eye-slash"></i>
                            </div>
                            <h5>No viewers yet</h5>
                            <p>
                                @if(request('search') || request('period'))
                                    No results match your current filters.
                                    <a href="{{ route('college.viewers') }}" style="color:var(--accent);">Clear filters</a>
                                @else
                                    Once students discover your college on the app, their visits will appear here.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($viewers->hasPages())
        <div class="pagination-wrap">
            <span>
                Showing {{ $viewers->firstItem() }}–{{ $viewers->lastItem() }}
                of {{ number_format($viewers->total()) }} viewers
            </span>
            <div class="page-links">
                {{ $viewers->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @else
        <div class="pagination-wrap">
            <span>{{ $viewers->total() }} {{ Str::plural('viewer', $viewers->total()) }}</span>
        </div>
        @endif
    </div>

</div>

{{-- Detail Modal --}}
<div class="modal-backdrop-custom" id="viewerModal" onclick="closeModal(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h5>Student Details</h5>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-avatar-row">
                <div class="modal-avatar" id="m-avatar" style="background:#1a56db;">U</div>
                <div>
                    <div style="font-size:18px; font-weight:700; color:var(--ink);" id="m-name">—</div>
                    <div style="font-size:13px; color:var(--ink-muted);" id="m-email">—</div>
                </div>
            </div>

            <div class="modal-info-grid">
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value" id="m-phone">—</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Visits</div>
                    <div class="info-value" id="m-total">—</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Viewed</div>
                    <div class="info-value" id="m-viewed">—</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Time Since</div>
                    <div class="info-value" id="m-ago">—</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openModal(data) {
        document.getElementById('m-avatar').textContent  = data.initial;
        document.getElementById('m-avatar').style.background = data.color;
        document.getElementById('m-name').textContent   = data.name;
        document.getElementById('m-email').textContent  = data.email;
        document.getElementById('m-phone').textContent  = data.phone;
        document.getElementById('m-total').textContent  = data.total + (data.total == 1 ? ' visit' : ' visits');
        document.getElementById('m-viewed').textContent = data.viewed;
        document.getElementById('m-ago').textContent    = data.ago;
        document.getElementById('viewerModal').classList.add('open');
    }

    function closeModal(e) {
        if (!e || e.target === document.getElementById('viewerModal')) {
            document.getElementById('viewerModal').classList.remove('open');
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush