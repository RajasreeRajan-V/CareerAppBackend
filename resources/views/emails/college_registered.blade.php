<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .box { max-width: 500px; margin: 40px auto; padding: 30px;
               border: 1px solid #ddd; border-radius: 8px; }
        .label { font-weight: bold; }
        .password { font-size: 22px; letter-spacing: 3px; color: #2563eb;
                    background: #f0f4ff; padding: 10px 20px;
                    border-radius: 6px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Welcome, {{ $collegeName }}!</h2>
        <p>Your college has been successfully registered. Here are your login credentials:</p>

        <p><span class="label">Email:</span> {{ $email }}</p>
        <p><span class="label">Password:</span></p>
        <div class="password">{{ $plainPassword }}</div>

        <p style="color:#e11d48; font-size:13px;">
            Please change your password after your first login.
        </p>
    </div>
</body>
</html>