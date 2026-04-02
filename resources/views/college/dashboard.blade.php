@extends('college.layout.app')

@section('header', 'College Dashboard')

@section('content')

<style>
    :root {
        --primary: #306060;
        --primary-light: #e8f0f0;
        --border: #e4e8ed;
        --text: #1a2535;
        --text-muted: #6b7a8d;
        --shadow: 0 2px 12px rgba(48,96,96,0.07);
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1;
    }
    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 3px;
    }

    .section-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .section-card .card-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .section-card .card-head a {
        font-size: 0.78rem;
        font-weight: 400;
        color: var(--primary);
        text-decoration: none;
    }
    .section-card .card-head a:hover { text-decoration: underline; }

    .notice-item {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f1f3f6;
        border-left: 3px solid transparent;
    }
    .notice-item:last-child { border-bottom: none; }
    .notice-item.urgent { border-left-color: #e8855a; }
    .notice-item.info   { border-left-color: var(--primary); }
    .notice-item .n-title { font-size: 0.84rem; font-weight: 500; color: var(--text); }
    .notice-item .n-meta  { font-size: 0.74rem; color: var(--text-muted); margin-top: 2px; }
    .badge-new {
        font-size: 0.62rem;
        background: #e8855a;
        color: #fff;
        padding: 1px 6px;
        border-radius: 50px;
        font-weight: 600;
        margin-left: 5px;
        vertical-align: middle;
    }

    .event-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f1f3f6;
    }
    .event-item:last-child { border-bottom: none; }
    .event-date {
        min-width: 42px;
        height: 42px;
        background: var(--primary-light);
        color: var(--primary);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.62rem;
        font-weight: 600;
        text-transform: uppercase;
        line-height: 1.1;
        flex-shrink: 0;
    }
    .event-date .day { font-size: 1rem; font-weight: 700; }
    .event-title { font-size: 0.84rem; font-weight: 500; color: var(--text); }
    .event-meta  { font-size: 0.74rem; color: var(--text-muted); margin-top: 2px; }
    .chip {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 50px;
        margin-left: auto;
        flex-shrink: 0;
    }
    .chip-exam    { background: #fdecea; color: #c62828; }
    .chip-event   { background: #e3f2fd; color: #1565c0; }
    .chip-holiday { background: #fff8e1; color: #9a6f00; }
    .chip-meet    { background: #e8f5e9; color: #2e7d32; }

    .prog-row { margin-bottom: 0.9rem; }
    .prog-row:last-child { margin-bottom: 0; }
    .prog-header { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px; }
    .prog-header .name { color: var(--text); font-weight: 500; }
    .prog-header .pct  { color: var(--primary); font-weight: 600; }
    .prog-track { height: 6px; background: #eef1f4; border-radius: 50px; overflow: hidden; }
    .prog-fill  { height: 100%; border-radius: 50px; }
    .pf-good   { background: #43a047; }
    .pf-warn   { background: #fb8c00; }
    .pf-danger { background: #e53935; }

    .dashboard-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .dashboard-actions .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fff !important;
        text-decoration: none;
        border: 0;
        transition: background-color .2s ease;
    }

    .dashboard-actions .action-btn:hover {
        filter: brightness(0.95);
    }
</style>

<div class="container-fluid px-0">

    {{-- Welcome Bar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h5 class="mb-0 fw-semibold" style="color:var(--text);">
                Welcome, {{ auth()->guard('college')->user()->college_name ?? 'College' }}
            </h5>
            <p class="mb-0 small" style="color:var(--text-muted);">{{ now()->format('l, d M Y') }}</p>
        </div>
        <div class="dashboard-actions">
            <a href="{{ route('college.collegeCourse.index') }}" class="action-btn" style="background:#2f7d7d;">
                <i class="fa-solid fa-book-open"></i> Courses
            </a>
            <a href="{{ route('college.feeStructure.index') }}" class="action-btn" style="background:#306060;">
                <i class="fa-solid fa-indian-rupee-sign"></i> Fee Structures
            </a>
            <a href="{{ route('college.collegeEdit.index') }}" class="action-btn" style="background:#4a90e2;">
                <i class="fa-solid fa-user-pen"></i> Edit Profile
            </a>
            <a href="{{ route('college.password.change') }}" class="action-btn" style="background:#f39c12;">
                <i class="fa-solid fa-key"></i> Change Password
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e8f0f0;color:var(--primary);">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($totalStudents ?? 2480) }}</div>
                    <div class="stat-label">Students</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fdf0ea;color:#e8855a;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalFaculty ?? 148 }}</div>
                    <div class="stat-label">Faculty</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalCourses ?? 86 }}</div>
                    <div class="stat-label">Courses</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fff8e1;color:#c9a84c;">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $placements ?? 312 }}</div>
                    <div class="stat-label">Placements</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Events + Notices --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="section-card p-3" style="text-align:center;color:var(--text-muted);">
                <i class="fa-solid fa-circle-info" style="color:var(--primary);margin-right:8px;"></i>
                No current notices or events to display.
            </div>
        </div>
    </div>

    {{-- Attendance + Recent Students --}}
   

</div>

@endsection