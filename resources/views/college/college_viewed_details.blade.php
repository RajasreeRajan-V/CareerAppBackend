@extends('college.layout.app')

@section('title', 'Profile Viewers')

@push('styles')
    <style>
        .pv-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        .pv-header h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1e2d3d;
            margin: 0;
        }

        .pv-header p {
            font-size: 13px;
            color: #888;
            margin: 4px 0 0;
        }

        /* Filter */
        .pv-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pv-filter label {
            font-size: 13px;
            color: #555;
        }

        .pv-filter input[type="date"] {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 13px;
            color: #1e2d3d;
            outline: none;
        }

        .pv-filter input[type="date"]:focus {
            border-color: #306060;
        }

        .btn-filter {
            background: #1e2d3d;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-filter:hover {
            background: #2c4160;
        }

        .btn-clear {
            background: none;
            border: 1px solid #ddd;
            color: #888;
            border-radius: 6px;
            padding: 7px 12px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-clear:hover {
            border-color: #1e2d3d;
            color: #1e2d3d;
        }

        /* Flash */
        .flash {
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .flash-success {
            background: #efffdf;
            border: 1px solid #b5e857;
            color: #3a6e00;
        }

        .flash-error {
            background: #fff0f0;
            border: 1px solid #ffb3b3;
            color: #8b0000;
        }

        /* Stat cards */
        .pv-stats {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .pv-stat {
            flex: 1;
            min-width: 160px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-left: 4px solid #306060;
            border-radius: 8px;
            padding: 18px 20px;
        }

        .pv-stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #999;
            margin-bottom: 8px;
        }

        .pv-stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1e2d3d;
            line-height: 1;
        }

        .pv-stat-sub {
            font-size: 12px;
            color: #aaa;
            margin-top: 6px;
        }

        /* Search row */
        .pv-search-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .pv-search {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .pv-search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            width: 15px;
            height: 15px;
        }

        .pv-search input {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 9px 12px 9px 36px;
            font-size: 13px;
            color: #1e2d3d;
            outline: none;
        }

        .pv-search input:focus {
            border-color: #306060;
        }

        .pv-search input::placeholder {
            color: #bbb;
        }

        .pv-count {
            background: #1e2d3d;
            color: #c8f03a;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 50px;
            white-space: nowrap;
        }

        /* Table */
        .pv-table-wrap {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .pv-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pv-table thead {
            background: #1e2d3d;
        }

        .pv-table thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #fff;
            padding: 12px 16px;
            text-align: left;
        }

        .pv-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .pv-table tbody tr:last-child {
            border-bottom: none;
        }

        .pv-table tbody tr:hover {
            background: #f8f9fb;
        }

        .pv-table td {
            padding: 12px 16px;
            font-size: 13px;
            color: #1e2d3d;
            vertical-align: middle;
        }

        .pv-user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pv-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #c8f03a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #1e2d3d;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .pv-user-name {
            font-weight: 500;
            font-size: 13px;
        }

        .pv-user-id {
            font-size: 11px;
            color: #aaa;
        }

        .pv-table td a {
            color: #1e2d3d;
            text-decoration: none;
            font-size: 13px;
        }

        .pv-table td a:hover {
            text-decoration: underline;
            color: #306060;
        }

        .pv-muted {
            color: #bbb;
            font-size: 13px;
        }

        .pv-date-day {
            font-size: 13px;
            font-weight: 500;
        }

        .pv-date-time {
            font-size: 11px;
            color: #aaa;
        }

        /* Empty state */
        .pv-empty {
            text-align: center;
            padding: 56px 24px;
        }

        .pv-empty i {
            font-size: 32px;
            color: #ccc;
            display: block;
            margin-bottom: 12px;
        }

        .pv-empty h3 {
            font-size: 17px;
            font-weight: 600;
            color: #1e2d3d;
            margin-bottom: 6px;
        }

        .pv-empty p {
            font-size: 13px;
            color: #aaa;
            margin: 0;
        }

        /* Pagination */
        .pv-pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pv-pagination a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: #fff;
            color: #1e2d3d;
            font-size: 13px;
            text-decoration: none;
        }

        .pv-pagination a:hover,
        .pv-pagination a.active {
            background: #1e2d3d;
            border-color: #1e2d3d;
            color: #fff;
        }

        .pv-pagination a.disabled {
            opacity: 0.35;
            pointer-events: none;
        }

        @media (max-width: 640px) {

            .pv-table thead th:nth-child(3),
            .pv-table td:nth-child(3) {
                display: none;
            }

            .pv-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="pv-header">
        <div>
            <h1>Profile Viewers</h1>
            <p>Students who visited your college page</p>
        </div>

        <form method="GET" action="{{ route('college.dashboard.viewers') }}" class="pv-filter">
            <label>From</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}">
            <label>To</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}">
            <button type="submit" class="btn-filter">Filter</button>
            @if (request('from_date') || request('to_date'))
                <a href="{{ route('college.dashboard.viewers') }}" class="btn-clear">Clear</a>
            @endif
        </form>
    </div>

    {{-- Stat cards --}}
    <div class="pv-stats">
    <div class="pv-stat">
        <div class="pv-stat-label">Total Views</div>
        <div class="pv-stat-value">{{ number_format($totalViews) }}</div>
        <div class="pv-stat-sub">All time page visits</div>
    </div>
    <div class="pv-stat">
        <div class="pv-stat-label">This Week</div>
        <div class="pv-stat-value">{{ number_format($thisWeekViews) }}</div>
        <div class="pv-stat-sub">Last 7 days</div>
    </div>
    <div class="pv-stat">
        <div class="pv-stat-label">Today</div>
        <div class="pv-stat-value">{{ number_format($todayViews) }}</div>
        <div class="pv-stat-sub">{{ now()->format('d M Y') }}</div>
    </div>
    <div class="pv-stat">
        <div class="pv-stat-label">Latest Visit</div>
        <div class="pv-stat-value" style="font-size:16px; padding-top:4px;">
            {{ $latestView ? $latestView->created_at->diffForHumans() : '—' }}
        </div>
        <div class="pv-stat-sub">Most recent viewer</div>
    </div>
</div>

    {{-- Search + count --}}
    <div class="pv-search-row">
        <div class="pv-search">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" id="tableSearch" placeholder="Search by name, email or phone…"
                oninput="filterTable(this.value)">
        </div>
        <span class="pv-count" id="visibleCount">{{ $viewers->total() }} students</span>
    </div>

    {{-- Table --}}
    <div class="pv-table-wrap">
        @if ($viewers->count())
            <table class="pv-table" id="viewersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Contact</th>
                        <th>Phone</th>
                        <th>Child</th>
                        <th>Viewed At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewers as $view)
                        <tr>
                            <td class="pv-muted">{{ $viewers->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="pv-user-cell">
                                    <div class="pv-avatar">
                                        {{ strtoupper(substr($view->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="pv-user-name">{{ $view->user->name ?? '—' }}</div>
                                        <div class="pv-user-id">#{{ $view->user->id ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($view->user?->email)
                                    <a href="mailto:{{ $view->user->email }}">{{ $view->user->email }}</a>
                                @else
                                    <span class="pv-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($view->user?->phone)
                                    <a href="tel:{{ $view->user->phone }}">{{ $view->user->phone }}</a>
                                @else
                                    <span class="pv-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($view->user?->children->isNotEmpty())
                                    @foreach ($view->user->children as $child)
                                        <div>
                                            <div class="pv-user-name">{{ $child->name }}</div>
                                            @if ($child->education_level)
                                                <div class="pv-user-id">{{ $child->education_level }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <span class="pv-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="pv-date-day">{{ $view->created_at->format('d M Y') }}</div>
                                <div class="pv-date-time">{{ $view->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="pv-empty">
                <i class="fas fa-eye"></i>
                <h3>No viewers yet</h3>
                <p>Students who visit your college profile will appear here.</p>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if ($viewers->hasPages())
        <div class="pv-pagination">
            <a class="{{ $viewers->onFirstPage() ? 'disabled' : '' }}"
                href="{{ $viewers->previousPageUrl() }}&from_date={{ request('from_date') }}&to_date={{ request('to_date') }}">←</a>

            @foreach ($viewers->getUrlRange(1, $viewers->lastPage()) as $page => $url)
                <a class="{{ $page == $viewers->currentPage() ? 'active' : '' }}"
                    href="{{ $url }}&from_date={{ request('from_date') }}&to_date={{ request('to_date') }}">
                    {{ $page }}
                </a>
            @endforeach

            <a class="{{ !$viewers->hasMorePages() ? 'disabled' : '' }}"
                href="{{ $viewers->nextPageUrl() }}&from_date={{ request('from_date') }}&to_date={{ request('to_date') }}">→</a>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        function filterTable(query) {
            const rows = document.querySelectorAll('#viewersTable tbody tr');
            const q = query.toLowerCase().trim();
            let visible = 0;

            rows.forEach(row => {
                const show = !q || row.innerText.toLowerCase().includes(q);
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            document.getElementById('visibleCount').textContent =
                visible + ' student' + (visible !== 1 ? 's' : '');
        }
    </script>
@endpush
