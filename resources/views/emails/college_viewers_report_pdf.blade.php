<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile Viewers Report</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size:11px; color:#1e2d3d; }

  .header { background:#1e2d3d; padding:20px 28px; margin-bottom:20px; }
  .header h1 { color:#c8f03a; font-size:18px; margin-bottom:4px; }
  .header p  { color:#8fa3b8; font-size:11px; }

  .stats { width:100%; margin-bottom:20px; border-collapse:collapse; }
  .stats td { width:25%; padding:12px 14px; background:#f8f9fb; border-left:3px solid #306060; }
  .stat-label { font-size:9px; text-transform:uppercase; color:#999; }
  .stat-value { font-size:20px; font-weight:700; color:#1e2d3d; margin-top:4px; }

  .section-title { font-size:11px; font-weight:700; color:#1e2d3d;
                   text-transform:uppercase; margin-bottom:8px;
                   padding-bottom:6px; border-bottom:2px solid #1e2d3d; }

  table.viewers { width:100%; border-collapse:collapse; font-size:10px; }
  table.viewers thead { background:#1e2d3d; }
  table.viewers thead th { color:#fff; padding:8px 10px; text-align:left; font-size:9px; text-transform:uppercase; }
  table.viewers tbody tr:nth-child(even) { background:#f8f9fb; }
  table.viewers tbody td { padding:7px 10px; color:#1e2d3d; border-bottom:1px solid #f0f0f0; }

  .footer { margin-top:24px; padding-top:12px; border-top:1px solid #e8e8e8; font-size:9px; color:#aaa; text-align:center; }
</style>
</head>
<body>

  <div class="header">
    <h1>Profile Viewers Report</h1>
    <p>{{ $collegeName }} &nbsp;·&nbsp; Daily Report — {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}</p>
  </div>

  <div style="padding:0 28px 28px;">

    <table class="stats">
      <tr>
        <td><div class="stat-label">Total Views</div><div class="stat-value">{{ $stats['total'] }}</div></td>
        <td><div class="stat-label">This Week</div><div class="stat-value">{{ $stats['week'] }}</div></td>
        <td><div class="stat-label">Today</div><div class="stat-value">{{ $stats['today'] }}</div></td>
        <td><div class="stat-label">In Report</div><div class="stat-value">{{ count($viewerRows) }}</div></td>
      </tr>
    </table>

    <div class="section-title">Viewer Details</div>

    @if(count($viewerRows))
    <table class="viewers">
      <thead>
        <tr>
          <th>#</th>
          <th>Student Name</th>
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
      <p style="color:#aaa;font-size:12px;margin-top:10px;">No viewers found.</p>
    @endif

    <div class="footer">
      Generated on {{ now()->format('d M Y, h:i A') }} &nbsp;·&nbsp; Sent to {{ $stats['recipient'] }}
    </div>

  </div>
</body>
</html>