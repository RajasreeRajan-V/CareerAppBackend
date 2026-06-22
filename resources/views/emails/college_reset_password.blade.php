<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: 'Inter', Arial, sans-serif;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .header {
            background: linear-gradient(135deg, #306060, #254848);
            padding: 30px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
        }

        .content {
            padding: 40px 35px;
            line-height: 1.8;
            font-size: 15px;
        }

        .content p {
            margin-bottom: 16px;
        }

        .button-wrapper {
            text-align: center;
            margin: 35px 0;
        }

        .button {
            display: inline-block;
            background: #306060;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 34px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
        }

        .button:hover {
            background: #254848;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #306060;
            padding: 14px 16px;
            margin-top: 24px;
            border-radius: 6px;
            font-size: 13px;
            color: #666;
        }

        .reset-link {
            word-break: break-all;
            color: #306060;
        }

        .footer {
            background: #fafafa;
            border-top: 1px solid #e5e5e5;
            text-align: center;
            padding: 18px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>Password Reset Request</h2>
    </div>

    <div class="content">

        <p>Hello <strong>{{ $collegeName }}</strong>,</p>

        <p>
            We received a request to reset the password for your college portal account.
            Click the button below to create a new password.
        </p>

        <div class="button-wrapper">
            <a href="{{ $resetUrl }}" class="button">
                Reset Password
            </a>
        </div>

        <p>
            This password reset link will expire in <strong>60 minutes</strong>.
            If you did not make this request, you can safely ignore this email and no changes will be made to your account.
        </p>

        <div class="info-box">
            <strong>Having trouble with the button?</strong><br><br>
            Copy and paste the following link into your browser:
            <br><br>
            <span class="reset-link">{{ $resetUrl }}</span>
        </div>

    </div>

    <div class="footer">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>

</div>

</body>
</html>

