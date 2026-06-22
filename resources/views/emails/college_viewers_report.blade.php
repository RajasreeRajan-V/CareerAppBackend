<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 11px;
        color: #1a1a2e;
        background: #fff;
        padding: 24px 30px;
    }

    /* ── Header ── */
    .header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
        color: #fff;
        border-radius: 8px;
        padding: 18px 22px;
        margin-bottom: 20px;
    }
    .header h1  { font-size: 18px; font-weight: 700; letter-spacing: .5px; }
    .header p   { font-size: 10px; opacity: .8; margin-top: 4px; }
    .header .badge {
        display: inline-block;
        background: rgba(255,255,255,.15);
        border-radius: 20px;
        padding: 2px 10px;
        font-size: 9px;
        margin-top: 6px;
    }

    /* ── Stats row ── */
    .stats {
        display: table;
        width: 100%;
        border-collapse: separate;
        border-spacing: 8px 0;
        margin-bottom: 20px;
    }
    .stat-box {
        display: table-cell;
        width: 33%;
        background: #f8f9fc;
        border: 1px solid #e4e7ef;
        border-top: 3px solid #0f3460;
        border-radius: 6px;
        padding: 10px 14px;
        text-align: center;
    }
    .stat-box .val  { font-size: 22px; font-weight: 700; color: #0f3460; }
    .stat-box .lbl  { font-size: 9px;  color: #6b7280; margin-top: 2px; text-transform: uppercase; letter-spacing: .5px; }

    /* ── Section title ── */
    .section-title {
        font-size: 12px;
        font-weight: 700;
        color: #0f3460;
        border-left: 4px solid #0f3460;
        padding-left: 8px;
        margin-bottom: 10px;
    }

    /* ── Table ── */
    table.viewers {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }
    table.viewers thead tr {
        background: #0f3460;
        color: #fff;
    }
    table.viewers thead th {
        padding: 8px 10px;
        text-align: left;
        font-weight: 600;
        letter-spacing: .3px;
    }
    table.viewers tbody tr:nth-child(even) { background: #f3f6fb; }
    table.viewers tbody tr:hover           { background: #e8edf7; }
    table.viewers tbody td {
        padding: 7px 10px;
        border-bottom: 1px solid #e9ecf2;
        color: #374151;
    }
    .no-data {
        text-align: center;
        padding: 20px;
        color: #9ca3af;
        font-style: italic;
    }

    /* ── Footer ── */
    .footer {
        margin-top: 24px;
        border-top: 1px solid #e4e7ef;
        padding-top: 10px;
        text-align: center;
        font-size: 9px;
        color: #9ca3af;
    }
</style>
</head>
<body>

{{-- ── Header ── --}}
<div class="header">
    <h1>Profile Viewers Report</h1>
    <p>{{ $collegeName }}</p>
    <span class="badge">
        @if($fromDate === $toDate)
            Date: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}
        @else
            {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}
            &nbsp;–&nbsp;
            {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }}
        @endif
    </span>
</div>

{{-- ── Stats ── --}}
<div class="stats">
    <div class="stat-box">
        <div class="val">{{ $stats['today'] }}</div>
        <div class="lbl">Views Today</div>
    </div>
    <div class="stat-box">
        <div class="val">{{ $stats['week'] }}</div>
        <div class="lbl">Views This Week</div>
    </div>
    <div class="stat-box">
        <div class="val">{{ $stats['total'] }}</div>
        <div class="lbl">Total Views (All Time)</div>
    </div>
</div>

{{-- ── Viewer table ── --}}
<div class="section-title">Today's Viewers ({{ count($viewerRows) }})</div>

@if(count($viewerRows) > 0)
<table class="viewers">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Viewed At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($viewerRows as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['email'] ?: '—' }}</td>
            <td>{{ $row['phone'] ?: '—' }}</td>
            <td>{{ $row['viewed_at'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="no-data">No viewer data available for this period.</p>
@endif

{{-- ── Footer ── --}}
<div class="footer">
    Generated on {{ now()->format('d M Y, h:i A') }} &nbsp;|&nbsp; {{ $collegeName }} &nbsp;|&nbsp; Confidential
</div>

</body>
</html>