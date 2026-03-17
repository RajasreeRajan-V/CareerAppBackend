@extends('college.layout.app')

@section('header', 'College Dashboard')

@section('content')

<link href="https://fonts.bunny.net/css?family=syne:400,500,600,700,800&family=dm-sans:300,400,500&display=swap" rel="stylesheet" />

<style>
    :root {
        --primary: #306060;
        --primary-dark: #254848;
        --primary-light: #e8f0f0;
        --accent: #e8855a;
        --accent-light: #fdf0ea;
        --gold: #c9a84c;
        --gold-light: #fdf6e3;
        --surface: #ffffff;
        --bg: #f4f6f8;
        --text: #1a2535;
        --text-muted: #6b7a8d;
        --border: #e4e8ed;
        --radius: 16px;
        --radius-sm: 10px;
        --shadow: 0 4px 24px rgba(48,96,96,0.08);
        --shadow-hover: 0 8px 36px rgba(48,96,96,0.16);
    }

    .dashboard-body {
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
    }

    /* ── HERO WELCOME BANNER ── */
    .hero-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, #3d7a7a 100%);
        border-radius: var(--radius);
        padding: 2.2rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .hero-banner::after {
        content: '';
        position: absolute;
        bottom: -60px; right: 100px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .hero-banner .college-name {
        font-family: 'Syne', sans-serif;
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }
    .hero-banner .semester-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }
    .hero-banner .quick-stats {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }
    .hero-banner .qs-item {
        text-align: center;
    }
    .hero-banner .qs-value {
        font-family: 'Syne', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1;
    }
    .hero-banner .qs-label {
        font-size: 0.72rem;
        opacity: 0.75;
        margin-top: 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .hero-banner .hero-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
    }
    .date-chip {
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.18);
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.9);
    }
    .notice-pill {
        background: var(--accent);
        border-radius: 50px;
        padding: 7px 18px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #fff;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .notice-pill:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(232,133,90,0.4); color: #fff; }

    /* ── STAT CARDS ── */
    .stat-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        transition: transform 0.25s, box-shadow 0.25s;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
    .stat-card .icon-wrap {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        flex-shrink: 0;
    }
    .stat-card .stat-value {
        font-family: 'Syne', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: var(--text);
    }
    .stat-card .stat-label {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 4px;
        font-weight: 500;
    }
    .stat-card .stat-change {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 10px;
    }
    .change-up   { background: #e8f5e9; color: #2e7d32; }
    .change-down { background: #fdecea; color: #c62828; }
    .change-neu  { background: #e3f2fd; color: #1565c0; }
    .stat-card .bg-decor {
        position: absolute; bottom: -20px; right: -20px;
        width: 90px; height: 90px;
        border-radius: 50%;
        opacity: 0.06;
    }

    /* ── SECTION TITLE ── */
    .section-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title .dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--primary);
    }
    .section-title a {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--primary);
        margin-left: auto;
        text-decoration: none;
    }
    .section-title a:hover { text-decoration: underline; }

    /* ── TABLE CARD ── */
    .table-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .table-card .card-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .table-card .card-header-custom .title {
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-card table thead th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        font-weight: 600;
        background: #f8fafc;
        padding: 0.8rem 1rem;
        border-bottom: 1px solid var(--border);
    }
    .table-card table tbody td {
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        border-bottom: 1px solid #f1f3f6;
        vertical-align: middle;
    }
    .table-card table tbody tr:last-child td { border-bottom: none; }
    .table-card table tbody tr:hover td { background: #f8fafc; }

    /* ── BADGE / STATUS ── */
    .badge-status {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-status::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .bs-active   { background: #e8f5e9; color: #2e7d32; }
    .bs-pending  { background: #fff3e0; color: #e65100; }
    .bs-closed   { background: #fdecea; color: #c62828; }
    .bs-info     { background: #e3f2fd; color: #1565c0; }

    /* ── EVENT / NOTICE LIST ── */
    .event-list { display: flex; flex-direction: column; gap: 0; }
    .event-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f3f6;
        transition: background 0.15s;
    }
    .event-item:last-child { border-bottom: none; }
    .event-item:hover { background: #f8fafc; }
    .event-date-box {
        min-width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-family: 'Syne', sans-serif;
        line-height: 1;
        flex-shrink: 0;
    }
    .event-date-box .ed-day { font-size: 1.1rem; font-weight: 800; }
    .event-date-box .ed-mon { font-size: 0.62rem; font-weight: 600; text-transform: uppercase; margin-top: 2px; opacity: 0.75; }
    .event-item .ev-title { font-size: 0.875rem; font-weight: 600; color: var(--text); }
    .event-item .ev-meta  { font-size: 0.75rem; color: var(--text-muted); margin-top: 3px; }
    .event-type-chip {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 50px;
        margin-left: auto;
        flex-shrink: 0;
        align-self: center;
    }
    .etc-exam   { background: #fdecea; color: #c62828; }
    .etc-event  { background: #e3f2fd; color: #1565c0; }
    .etc-holiday{ background: var(--gold-light); color: #9a6f00; }
    .etc-meet   { background: #e8f5e9; color: #2e7d32; }

    /* ── ATTENDANCE PROGRESS ── */
    .attendance-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        padding: 1.5rem;
    }
    .subject-row { margin-bottom: 1.1rem; }
    .subject-row:last-child { margin-bottom: 0; }
    .subject-row .subj-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px; }
    .subject-row .subj-name { font-size: 0.82rem; font-weight: 600; color: var(--text); }
    .subject-row .subj-pct  { font-family: 'Syne', sans-serif; font-size: 0.82rem; font-weight: 700; color: var(--primary); }
    .prog-bar-wrap {
        height: 7px;
        background: var(--border);
        border-radius: 50px;
        overflow: hidden;
    }
    .prog-bar-fill {
        height: 100%;
        border-radius: 50px;
        transition: width 1s ease;
    }
    .pf-good   { background: linear-gradient(90deg, #43a047, #66bb6a); }
    .pf-warn   { background: linear-gradient(90deg, #fb8c00, #ffa726); }
    .pf-danger { background: linear-gradient(90deg, #e53935, #ef5350); }

    /* ── QUICK ACTION BUTTONS ── */
    .quick-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    .qa-btn {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        color: var(--text);
        cursor: pointer;
    }
    .qa-btn:hover { border-color: var(--primary); background: var(--primary-light); color: var(--primary-dark); transform: translateY(-2px); box-shadow: var(--shadow); }
    .qa-btn .qa-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .qa-btn .qa-label { font-size: 0.75rem; font-weight: 600; text-align: center; }

    /* ── NOTICE BOARD ── */
    .notice-item {
        padding: 1rem 1.5rem;
        border-left: 3px solid transparent;
        border-bottom: 1px solid #f1f3f6;
        transition: background 0.15s;
        cursor: pointer;
    }
    .notice-item:hover { background: #f8fafc; }
    .notice-item:last-child { border-bottom: none; }
    .notice-item.ni-urgent { border-left-color: var(--accent); }
    .notice-item.ni-info   { border-left-color: var(--primary); }
    .notice-item.ni-exam   { border-left-color: #e53935; }
    .notice-item .ni-title { font-size: 0.85rem; font-weight: 600; color: var(--text); }
    .notice-item .ni-meta  { font-size: 0.72rem; color: var(--text-muted); margin-top: 3px; }
    .notice-item .ni-new   { font-size: 0.62rem; background: var(--accent); color: #fff; padding: 2px 7px; border-radius: 50px; font-weight: 700; margin-left: 6px; vertical-align: middle; }

    /* ── CGPA RING (CSS-only) ── */
    .cgpa-ring-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .cgpa-ring {
        position: relative;
        width: 120px;
        height: 120px;
    }
    .cgpa-ring svg { transform: rotate(-90deg); }
    .cgpa-ring .ring-bg  { fill: none; stroke: var(--border); stroke-width: 10; }
    .cgpa-ring .ring-val { fill: none; stroke: var(--primary); stroke-width: 10; stroke-linecap: round;
        stroke-dasharray: 314; stroke-dashoffset: 50; transition: stroke-dashoffset 1s ease; }
    .cgpa-ring .ring-text {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
    }
    .cgpa-ring .ring-text .rv { font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 800; color: var(--primary); }
    .cgpa-ring .ring-text .rl { font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .hero-banner { padding: 1.5rem; }
        .hero-banner .college-name { font-size: 1.3rem; }
        .hero-banner .quick-stats { gap: 1.2rem; }
        .hero-banner .qs-value { font-size: 1.2rem; }
        .quick-actions { grid-template-columns: 1fr 1fr; }
    }
</style>

<div class="dashboard-body">

    {{-- ════════════════════════════════════════
         HERO WELCOME BANNER
    ════════════════════════════════════════ --}}
    <div class="hero-banner mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-1">
                    <div style="width:44px;height:44px;background:rgba(255,255,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                        🎓
                    </div>
                    <div>
                        <div class="college-name">{{ config('app.name', 'Excellence College of Arts & Science') }}</div>
                        <div class="semester-badge">
                            <i class="fa-solid fa-calendar-days" style="font-size:0.7rem;"></i>
                            Semester VI &nbsp;·&nbsp; Batch 2022–2025 &nbsp;·&nbsp; Academic Year {{ date('Y') }}
                        </div>
                    </div>
                </div>
                <div class="quick-stats mt-3">
                    <div class="qs-item">
                        <div class="qs-value">2,480</div>
                        <div class="qs-label">Total Students</div>
                    </div>
                    <div class="qs-item">
                        <div class="qs-value">148</div>
                        <div class="qs-label">Faculty</div>
                    </div>
                    <div class="qs-item">
                        <div class="qs-value">32</div>
                        <div class="qs-label">Departments</div>
                    </div>
                    <div class="qs-item">
                        <div class="qs-value">94%</div>
                        <div class="qs-label">Pass Rate</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hero-right">
                    <div class="date-chip">
                        <i class="fa-regular fa-clock me-1"></i>
                        {{ now()->format('l, d M Y') }}
                    </div>
                    <a href="#" class="notice-pill mt-2">
                        <i class="fa-solid fa-bullhorn" style="font-size:0.75rem;"></i>
                        3 New Notices
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         STAT CARDS ROW
    ════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:#e8f0f0;color:var(--primary);">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-value">{{ number_format($totalStudents ?? 2480) }}</div>
                <div class="stat-label">Enrolled Students</div>
                <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +12% this sem</div>
                <div class="bg-decor" style="background:var(--primary);"></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:#fdf0ea;color:var(--accent);">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div class="stat-value">{{ $totalFaculty ?? 148 }}</div>
                <div class="stat-label">Faculty Members</div>
                <div class="stat-change change-neu"><i class="fa-solid fa-minus"></i> Stable</div>
                <div class="bg-decor" style="background:var(--accent);"></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:#e8f5e9;color:#2e7d32;">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <div class="stat-value">{{ $totalCourses ?? 86 }}</div>
                <div class="stat-label">Active Courses</div>
                <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> +4 new</div>
                <div class="bg-decor" style="background:#2e7d32;"></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="icon-wrap" style="background:var(--gold-light);color:var(--gold);">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div class="stat-value">{{ $placements ?? 312 }}</div>
                <div class="stat-label">Placements (2024)</div>
                <div class="stat-change change-up"><i class="fa-solid fa-arrow-trend-up"></i> Best ever</div>
                <div class="bg-decor" style="background:var(--gold);"></div>
            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════
         MIDDLE ROW  — Upcoming Events + Notice Board
    ════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- Upcoming Events --}}
        <div class="col-lg-6">
            <div class="section-title">
                <div class="dot"></div> Upcoming Events & Exams
                <a href="#">View All <i class="fa-solid fa-arrow-right fa-xs"></i></a>
            </div>
            <div class="table-card">
                <div class="event-list">

                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="ed-day">15</span>
                            <span class="ed-mon">Mar</span>
                        </div>
                        <div class="flex-1">
                            <div class="ev-title">Internal Assessment – Mathematics</div>
                            <div class="ev-meta"><i class="fa-regular fa-clock fa-xs me-1"></i>09:00 AM &nbsp;·&nbsp; Hall A, B, C</div>
                        </div>
                        <div class="event-type-chip etc-exam">Exam</div>
                    </div>

                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="ed-day">18</span>
                            <span class="ed-mon">Mar</span>
                        </div>
                        <div class="flex-1">
                            <div class="ev-title">Annual Tech Fest – TechNova 2025</div>
                            <div class="ev-meta"><i class="fa-regular fa-clock fa-xs me-1"></i>10:00 AM &nbsp;·&nbsp; Main Auditorium</div>
                        </div>
                        <div class="event-type-chip etc-event">Event</div>
                    </div>

                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="ed-day">22</span>
                            <span class="ed-mon">Mar</span>
                        </div>
                        <div class="flex-1">
                            <div class="ev-title">Holi – College Holiday</div>
                            <div class="ev-meta"><i class="fa-regular fa-calendar fa-xs me-1"></i>Full Day Holiday</div>
                        </div>
                        <div class="event-type-chip etc-holiday">Holiday</div>
                    </div>

                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="ed-day">25</span>
                            <span class="ed-mon">Mar</span>
                        </div>
                        <div class="flex-1">
                            <div class="ev-title">Parent–Teacher Meeting (Sem VI)</div>
                            <div class="ev-meta"><i class="fa-regular fa-clock fa-xs me-1"></i>11:00 AM &nbsp;·&nbsp; Seminar Hall</div>
                        </div>
                        <div class="event-type-chip etc-meet">Meeting</div>
                    </div>

                    <div class="event-item">
                        <div class="event-date-box">
                            <span class="ed-day">28</span>
                            <span class="ed-mon">Mar</span>
                        </div>
                        <div class="flex-1">
                            <div class="ev-title">End Semester Exam – Physics</div>
                            <div class="ev-meta"><i class="fa-regular fa-clock fa-xs me-1"></i>02:00 PM &nbsp;·&nbsp; Exam Hall 1</div>
                        </div>
                        <div class="event-type-chip etc-exam">Exam</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Notice Board --}}
        <div class="col-lg-6">
            <div class="section-title">
                <div class="dot"></div> Notice Board
                <a href="#">All Notices <i class="fa-solid fa-arrow-right fa-xs"></i></a>
            </div>
            <div class="table-card">

                <div class="notice-item ni-urgent">
                    <div class="ni-title">
                        Scholarship Application – Last Date Extended
                        <span class="ni-new">New</span>
                    </div>
                    <div class="ni-meta"><i class="fa-regular fa-calendar fa-xs me-1"></i>Posted: 10 Mar 2025 &nbsp;·&nbsp; Admin Office</div>
                </div>

                <div class="notice-item ni-exam">
                    <div class="ni-title">
                        Hall Ticket Release – End Semester Exams
                        <span class="ni-new">New</span>
                    </div>
                    <div class="ni-meta"><i class="fa-regular fa-calendar fa-xs me-1"></i>Posted: 09 Mar 2025 &nbsp;·&nbsp; Examination Cell</div>
                </div>

                <div class="notice-item ni-info">
                    <div class="ni-title">Library Working Hours Updated for Exam Season</div>
                    <div class="ni-meta"><i class="fa-regular fa-calendar fa-xs me-1"></i>Posted: 07 Mar 2025 &nbsp;·&nbsp; Library</div>
                </div>

                <div class="notice-item ni-info">
                    <div class="ni-title">Campus Recruitment – Infosys Drive on March 20</div>
                    <div class="ni-meta"><i class="fa-regular fa-calendar fa-xs me-1"></i>Posted: 05 Mar 2025 &nbsp;·&nbsp; Placement Cell</div>
                </div>

                <div class="notice-item ni-info">
                    <div class="ni-title">Anti-Ragging Committee Meeting – Attendance Mandatory</div>
                    <div class="ni-meta"><i class="fa-regular fa-calendar fa-xs me-1"></i>Posted: 03 Mar 2025 &nbsp;·&nbsp; Principal Office</div>
                </div>

                <div class="notice-item ni-info">
                    <div class="ni-title">UG Project Submission Guidelines Updated</div>
                    <div class="ni-meta"><i class="fa-regular fa-calendar fa-xs me-1"></i>Posted: 01 Mar 2025 &nbsp;·&nbsp; Academics</div>
                </div>

            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════
         BOTTOM ROW — Student Table + Attendance + Quick Actions
    ════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- Recent Students --}}
        <div class="col-lg-6">
            <div class="section-title">
                <div class="dot"></div> Recently Admitted Students
                <a href="#">View All <i class="fa-solid fa-arrow-right fa-xs"></i></a>
            </div>
            <div class="table-card">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Dept</th>
                            <th>Year</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $students = [
                                ['name'=>'Ananya Krishnan',   'dept'=>'CS',    'year'=>'III', 'status'=>'active',  'color'=>'#306060'],
                                ['name'=>'Rohan Mehta',       'dept'=>'ECE',   'year'=>'II',  'status'=>'active',  'color'=>'#e8855a'],
                                ['name'=>'Priya Nair',        'dept'=>'Maths', 'year'=>'I',   'status'=>'pending', 'color'=>'#c9a84c'],
                                ['name'=>'Aditya Verma',      'dept'=>'BBA',   'year'=>'III', 'status'=>'active',  'color'=>'#7b68ee'],
                                ['name'=>'Sneha Thomas',      'dept'=>'Chem',  'year'=>'II',  'status'=>'active',  'color'=>'#2e7d32'],
                                ['name'=>'Mohammed Faiz',     'dept'=>'Phys',  'year'=>'I',   'status'=>'pending', 'color'=>'#1565c0'],
                            ];
                        @endphp
                        @foreach($students as $s)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:30px;height:30px;border-radius:50%;background:{{ $s['color'] }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;font-weight:700;flex-shrink:0;">
                                        {{ strtoupper(substr($s['name'],0,1)) }}
                                    </div>
                                    <span style="font-weight:500;font-size:0.82rem;">{{ $s['name'] }}</span>
                                </div>
                            </td>
                            <td><span style="background:#f1f5f9;color:#475569;font-size:0.72rem;font-weight:600;padding:2px 8px;border-radius:4px;">{{ $s['dept'] }}</span></td>
                            <td style="font-size:0.82rem;color:var(--text-muted);">Yr {{ $s['year'] }}</td>
                            <td>
                                @if($s['status'] === 'active')
                                    <span class="badge-status bs-active">Active</span>
                                @else
                                    <span class="badge-status bs-pending">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Attendance Overview + Quick Actions --}}
        <div class="col-lg-6">
            <div class="row g-3 h-100">

                {{-- Attendance --}}
                <div class="col-12">
                    <div class="section-title">
                        <div class="dot"></div> Department Attendance (This Month)
                        <a href="#">Details <i class="fa-solid fa-arrow-right fa-xs"></i></a>
                    </div>
                    <div class="attendance-card">
                        @php
                            $subjects = [
                                ['name'=>'Computer Science',    'pct'=>88, 'cls'=>'pf-good'],
                                ['name'=>'Electronics & Comm.', 'pct'=>75, 'cls'=>'pf-warn'],
                                ['name'=>'Mathematics',         'pct'=>92, 'cls'=>'pf-good'],
                                ['name'=>'Business Admin.',     'pct'=>61, 'cls'=>'pf-danger'],
                                ['name'=>'Chemistry',           'pct'=>84, 'cls'=>'pf-good'],
                            ];
                        @endphp
                        @foreach($subjects as $sub)
                        <div class="subject-row">
                            <div class="subj-header">
                                <span class="subj-name">{{ $sub['name'] }}</span>
                                <span class="subj-pct">{{ $sub['pct'] }}%</span>
                            </div>
                            <div class="prog-bar-wrap">
                                <div class="prog-bar-fill {{ $sub['cls'] }}" style="width:{{ $sub['pct'] }}%;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="col-12">
                    <div class="section-title">
                        <div class="dot"></div> Quick Actions
                    </div>
                    <div class="quick-actions">
                        <a href="#" class="qa-btn">
                            <div class="qa-icon" style="background:#e8f0f0;color:var(--primary);">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <span class="qa-label">Admit Student</span>
                        </a>
                        <a href="#" class="qa-btn">
                            <div class="qa-icon" style="background:#fdf6e3;color:var(--gold);">
                                <i class="fa-solid fa-calendar-plus"></i>
                            </div>
                            <span class="qa-label">Timetable</span>
                        </a>
                        <a href="#" class="qa-btn">
                            <div class="qa-icon" style="background:#fdecea;color:#c62828;">
                                <i class="fa-solid fa-file-pen"></i>
                            </div>
                            <span class="qa-label">Schedule Exam</span>
                        </a>
                        <a href="#" class="qa-btn">
                            <div class="qa-icon" style="background:#e3f2fd;color:#1565c0;">
                                <i class="fa-solid fa-chart-bar"></i>
                            </div>
                            <span class="qa-label">Reports</span>
                        </a>
                        <a href="#" class="qa-btn">
                            <div class="qa-icon" style="background:#e8f5e9;color:#2e7d32;">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </div>
                            <span class="qa-label">Add Faculty</span>
                        </a>
                        <a href="#" class="qa-btn">
                            <div class="qa-icon" style="background:#fdf0ea;color:var(--accent);">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                            </div>
                            <span class="qa-label">Fee Management</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ════════════════════════════════════════
         BOTTOM BAND — CGPA + Fee Stats + Top Performers
    ════════════════════════════════════════ --}}
    <div class="row g-3">

        {{-- CGPA Ring --}}
        <div class="col-md-4">
            <div class="section-title">
                <div class="dot"></div> College CGPA Average
            </div>
            <div class="table-card p-4">
                <div class="cgpa-ring-wrap">
                    <div class="cgpa-ring">
                        <svg viewBox="0 0 120 120" width="120" height="120">
                            <circle class="ring-bg"  cx="60" cy="60" r="50"/>
                            <circle class="ring-val" cx="60" cy="60" r="50"
                                    style="stroke-dashoffset: {{ 314 - (314 * 0.795) }};"/>
                        </svg>
                        <div class="ring-text">
                            <span class="rv">7.95</span>
                            <span class="rl">/ 10.0</span>
                        </div>
                    </div>
                    <p class="text-center mt-2" style="font-size:0.78rem;color:var(--text-muted);">Overall CGPA across all departments</p>
                    <div class="row text-center w-100 mt-1">
                        <div class="col-4">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:var(--primary);">9.1</div>
                            <div style="font-size:0.65rem;color:var(--text-muted);">CS Dept</div>
                        </div>
                        <div class="col-4">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:var(--primary);">7.6</div>
                            <div style="font-size:0.65rem;color:var(--text-muted);">ECE Dept</div>
                        </div>
                        <div class="col-4">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:var(--primary);">8.3</div>
                            <div style="font-size:0.65rem;color:var(--text-muted);">Maths</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fee Collection --}}
        <div class="col-md-4">
            <div class="section-title">
                <div class="dot"></div> Fee Collection Status
            </div>
            <div class="table-card p-4">
                @php
                    $feeStats = [
                        ['label'=>'Tuition Fee',    'collected'=>92, 'color'=>'#306060'],
                        ['label'=>'Exam Fee',        'collected'=>78, 'color'=>'#e8855a'],
                        ['label'=>'Library Fee',     'collected'=>85, 'color'=>'#c9a84c'],
                        ['label'=>'Sports Fee',      'collected'=>65, 'color'=>'#7b68ee'],
                    ];
                @endphp
                @foreach($feeStats as $fee)
                <div class="subject-row">
                    <div class="subj-header">
                        <span class="subj-name">{{ $fee['label'] }}</span>
                        <span style="font-family:'Syne',sans-serif;font-size:0.82rem;font-weight:700;color:{{ $fee['color'] }};">{{ $fee['collected'] }}%</span>
                    </div>
                    <div class="prog-bar-wrap">
                        <div style="height:100%;width:{{ $fee['collected'] }}%;background:{{ $fee['color'] }};border-radius:50px;transition:width 1s ease;"></div>
                    </div>
                </div>
                @endforeach
                <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top:1px solid var(--border);">
                    <span style="font-size:0.78rem;color:var(--text-muted);">Total Collected</span>
                    <span style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.1rem;color:var(--primary);">₹ 84.2L</span>
                </div>
            </div>
        </div>

        {{-- Top Performers --}}
        <div class="col-md-4">
            <div class="section-title">
                <div class="dot"></div> Top Performing Students
                <a href="#">View All <i class="fa-solid fa-arrow-right fa-xs"></i></a>
            </div>
            <div class="table-card">
                @php
                    $toppers = [
                        ['name'=>'Ananya Krishnan', 'dept'=>'CS',    'cgpa'=>9.8, 'rank'=>1, 'color'=>'#c9a84c'],
                        ['name'=>'Priya Menon',     'dept'=>'Maths', 'cgpa'=>9.6, 'rank'=>2, 'color'=>'#9e9e9e'],
                        ['name'=>'Arjun Das',       'dept'=>'CS',    'cgpa'=>9.4, 'rank'=>3, 'color'=>'#cd7f32'],
                        ['name'=>'Kavya Sharma',    'dept'=>'ECE',   'cgpa'=>9.2, 'rank'=>4, 'color'=>'#306060'],
                        ['name'=>'Rahul Pillai',    'dept'=>'BBA',   'cgpa'=>9.0, 'rank'=>5, 'color'=>'#306060'],
                    ];
                @endphp
                @foreach($toppers as $t)
                <div class="event-item" style="padding:0.85rem 1.25rem;">
                    <div style="width:28px;height:28px;border-radius:50%;background:{{ $t['color'] }};display:flex;align-items:center;justify-content:center;color:#fff;font-family:'Syne',sans-serif;font-size:0.7rem;font-weight:800;flex-shrink:0;">
                        {{ $t['rank'] }}
                    </div>
                    <div class="flex-1 ms-1">
                        <div style="font-size:0.82rem;font-weight:600;color:var(--text);">{{ $t['name'] }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);">{{ $t['dept'] }}</div>
                    </div>
                    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.9rem;color:var(--primary);">
                        {{ $t['cgpa'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

@endsection