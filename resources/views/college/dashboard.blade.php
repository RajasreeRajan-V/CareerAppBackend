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
        <a href="#" class="btn btn-sm" style="background:var(--primary);color:#fff;border-radius:8px;font-size:0.8rem;">
            <i class="fa-solid fa-bullhorn me-1"></i> 3 New Notices
        </a>
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

        {{-- Upcoming Events --}}
        <div class="col-lg-6">
            <div class="section-card">
                <div class="card-head">
                    Upcoming Events
                    <a href="#">View all</a>
                </div>
                <div class="event-item">
                    <div class="event-date"><span class="day">15</span>Mar</div>
                    <div>
                        <div class="event-title">Internal Assessment – Mathematics</div>
                        <div class="event-meta">09:00 AM · Hall A, B, C</div>
                    </div>
                    <span class="chip chip-exam">Exam</span>
                </div>
                <div class="event-item">
                    <div class="event-date"><span class="day">18</span>Mar</div>
                    <div>
                        <div class="event-title">Annual Tech Fest – TechNova 2025</div>
                        <div class="event-meta">10:00 AM · Main Auditorium</div>
                    </div>
                    <span class="chip chip-event">Event</span>
                </div>
                <div class="event-item">
                    <div class="event-date"><span class="day">22</span>Mar</div>
                    <div>
                        <div class="event-title">Holi – College Holiday</div>
                        <div class="event-meta">Full Day Holiday</div>
                    </div>
                    <span class="chip chip-holiday">Holiday</span>
                </div>
                <div class="event-item">
                    <div class="event-date"><span class="day">25</span>Mar</div>
                    <div>
                        <div class="event-title">Parent–Teacher Meeting (Sem VI)</div>
                        <div class="event-meta">11:00 AM · Seminar Hall</div>
                    </div>
                    <span class="chip chip-meet">Meeting</span>
                </div>
            </div>
        </div>

        {{-- Notice Board --}}
        <div class="col-lg-6">
            <div class="section-card">
                <div class="card-head">
                    Notice Board
                    <a href="#">View all</a>
                </div>
                <div class="notice-item urgent">
                    <div class="n-title">Scholarship Application – Last Date Extended <span class="badge-new">New</span></div>
                    <div class="n-meta">10 Mar 2025 · Admin Office</div>
                </div>
                <div class="notice-item urgent">
                    <div class="n-title">Hall Ticket Release – End Semester Exams <span class="badge-new">New</span></div>
                    <div class="n-meta">09 Mar 2025 · Examination Cell</div>
                </div>
                <div class="notice-item info">
                    <div class="n-title">Library Working Hours Updated for Exam Season</div>
                    <div class="n-meta">07 Mar 2025 · Library</div>
                </div>
                <div class="notice-item info">
                    <div class="n-title">Campus Recruitment – Infosys Drive on March 20</div>
                    <div class="n-meta">05 Mar 2025 · Placement Cell</div>
                </div>
                <div class="notice-item info">
                    <div class="n-title">UG Project Submission Guidelines Updated</div>
                    <div class="n-meta">01 Mar 2025 · Academics</div>
                </div>
            </div>
        </div>

    </div>

    {{-- Attendance + Recent Students --}}
    <div class="row g-3">

        {{-- Attendance --}}
        <div class="col-lg-5">
            <div class="section-card p-3">
                <div class="card-head px-0 pt-0" style="border-bottom:1px solid var(--border);margin-bottom:1rem;">
                    Department Attendance
                    <a href="#">Details</a>
                </div>
                @php
                    $subjects = [
                        ['name' => 'Computer Science',    'pct' => 88, 'cls' => 'pf-good'],
                        ['name' => 'Electronics & Comm.', 'pct' => 75, 'cls' => 'pf-warn'],
                        ['name' => 'Mathematics',         'pct' => 92, 'cls' => 'pf-good'],
                        ['name' => 'Business Admin.',     'pct' => 61, 'cls' => 'pf-danger'],
                        ['name' => 'Chemistry',           'pct' => 84, 'cls' => 'pf-good'],
                    ];
                @endphp
                @foreach($subjects as $sub)
                <div class="prog-row">
                    <div class="prog-header">
                        <span class="name">{{ $sub['name'] }}</span>
                        <span class="pct">{{ $sub['pct'] }}%</span>
                    </div>
                    <div class="prog-track">
                        <div class="prog-fill {{ $sub['cls'] }}" style="width:{{ $sub['pct'] }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Students --}}
        <div class="col-lg-7">
            <div class="section-card">
                <div class="card-head">
                    Recently Admitted Students
                    <a href="#">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:0.84rem;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th class="px-3 py-2 text-muted fw-semibold" style="font-size:0.72rem;letter-spacing:0.4px;">Student</th>
                                <th class="px-3 py-2 text-muted fw-semibold" style="font-size:0.72rem;letter-spacing:0.4px;">Dept</th>
                                <th class="px-3 py-2 text-muted fw-semibold" style="font-size:0.72rem;letter-spacing:0.4px;">Year</th>
                                <th class="px-3 py-2 text-muted fw-semibold" style="font-size:0.72rem;letter-spacing:0.4px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $students = [
                                    ['name' => 'Ananya Krishnan', 'dept' => 'CS',    'year' => 'III', 'status' => 'active',  'color' => '#306060'],
                                    ['name' => 'Rohan Mehta',     'dept' => 'ECE',   'year' => 'II',  'status' => 'active',  'color' => '#e8855a'],
                                    ['name' => 'Priya Nair',      'dept' => 'Maths', 'year' => 'I',   'status' => 'pending', 'color' => '#c9a84c'],
                                    ['name' => 'Aditya Verma',    'dept' => 'BBA',   'year' => 'III', 'status' => 'active',  'color' => '#7b68ee'],
                                    ['name' => 'Sneha Thomas',    'dept' => 'Chem',  'year' => 'II',  'status' => 'active',  'color' => '#2e7d32'],
                                ];
                            @endphp
                            @foreach($students as $s)
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:28px;height:28px;border-radius:50%;background:{{ $s['color'] }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.68rem;font-weight:700;flex-shrink:0;">
                                            {{ strtoupper(substr($s['name'],0,1)) }}
                                        </div>
                                        {{ $s['name'] }}
                                    </div>
                                </td>
                                <td class="px-3 py-2 text-muted">{{ $s['dept'] }}</td>
                                <td class="px-3 py-2 text-muted">Yr {{ $s['year'] }}</td>
                                <td class="px-3 py-2">
                                    @if($s['status'] === 'active')
                                        <span style="font-size:0.7rem;font-weight:600;padding:2px 9px;border-radius:50px;background:#e8f5e9;color:#2e7d32;">Active</span>
                                    @else
                                        <span style="font-size:0.7rem;font-weight:600;padding:2px 9px;border-radius:50px;background:#fff3e0;color:#e65100;">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection