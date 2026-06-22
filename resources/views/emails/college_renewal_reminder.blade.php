<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Renewal Reminder</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f0f4f4;
            padding: 40px 20px;
            color: #2f2f2f;
        }
        .wrapper {
            max-width: 580px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #306060, #1e4242);
            padding: 36px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .header p {
            color: rgba(255,255,255,0.75);
            font-size: 13.5px;
            margin-top: 6px;
        }
        .body {
            padding: 36px 40px;
        }
        .alert-badge {
            display: inline-block;
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            font-size: 12.5px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        .body h2 {
            font-size: 19px;
            color: #1e4242;
            margin-bottom: 12px;
        }
        .body p {
            font-size: 14.5px;
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 14px;
        }
        .info-box {
            background: #f0f7f7;
            border-left: 4px solid #306060;
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            margin: 22px 0;
            font-size: 14px;
            color: #2f2f2f;
        }
        .info-box strong {
            display: block;
            margin-bottom: 4px;
            color: #306060;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-wrap {
            text-align: center;
            margin: 28px 0 10px;
        }
        .btn {
            display: inline-block;
            background: #306060;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .footer {
            background: #f8fafa;
            border-top: 1px solid #e5eaea;
            padding: 22px 40px;
            text-align: center;
            font-size: 12.5px;
            color: #9ca3af;
            line-height: 1.8;
        }
        .footer a {
            color: #306060;
            text-decoration: none;
        }
        .expiry-chip {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            font-weight: 700;
            font-size: 13px;
            padding: 3px 12px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        {{-- Header --}}
        <div class="header">
            <h1>Careers Portal</h1>
            <p>Subscription Renewal Notice</p>
        </div>

        {{-- Body --}}
        <div class="body">
            <span class="alert-badge">⚠ Action Required</span>

            <h2>Hello, {{ $college->name }}!</h2>

            <p>
                We're writing to let you know that your <strong>Careers Portal subscription</strong>
                is expiring in <strong>{{ $daysLeft }} day{{ $daysLeft > 1 ? 's' : '' }}</strong>.
                Once expired, your college account and all associated student placement data
                will be inaccessible until renewal is complete.
            </p>

            <div class="info-box">
                <strong>Subscription Details</strong>
                Registered Email &nbsp;&nbsp;: {{ $college->email }}<br>
                Subscription Start : {{ \Carbon\Carbon::parse($college->created_at)->format('d M Y') }}<br>
                Expiry Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                <span class="expiry-chip">{{ $expiryDate }}</span>
            </div>

            <p>
                To avoid any disruption to your college's placement activities, please renew
                your subscription before the expiry date.
            </p>

            <div class="btn-wrap">
                <a href="{{ url('renew/' . $college->id) }}" class="btn">
                    Renew My Subscription →
                </a>
            </div>

            <p style="font-size:13px; color:#9ca3af; text-align:center;">
                If you have already renewed, please ignore this email.
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            © {{ date('Y') }} Careers Portal. All rights reserved.<br>
            Questions? <a href="mailto:support@careersportal.com">support@careersportal.com</a><br>
            <span style="font-size:11.5px;">
                This is an automated reminder. Please do not reply to this email.
            </span>
        </div>

    </div>
</div>
</body>
</html>