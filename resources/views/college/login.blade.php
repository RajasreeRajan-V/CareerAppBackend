<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>College Portal | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #306060;
            --primary-dark: #254f4f;
            --white: #ffffff;
            --text-dark: #2f2f2f;
            --text-light: #6b7280;
            --border: #d1d5db;
        }

        * {
            box-sizing: border-box;
            font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(160deg, #306060, #1e4242);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--white);
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 14px;
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.18),
                0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 28px;
        }

        .logo-ring {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .logo-ring::before {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            border: 4px solid rgba(48, 96, 96, 0.35);
        }

        .logo-ring::after {
            content: "";
            position: absolute;
            inset: 2px;
            border-radius: 50%;
            border: 2px solid rgba(79, 163, 163, 0.8);
        }

        .logo {
            width: 120px;
            height: 120px;
            background: rgba(48, 96, 96, 0.08);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo i {
            font-size: 60px;
            color: var(--primary);
            transition: 0.3s ease;
        }

        .logo i:hover {
            color: var(--primary-dark);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        h2 {
            text-align: center;
            color: var(--text-dark);
            font-size: 22px;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(48, 96, 96, 0.15);
        }

        .login-btn {
            width: 100%;
            margin-top: 10px;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 13px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
        }

        .login-btn:hover {
            background: var(--primary-dark);
        }

        .footer-text {
            margin-top: 22px;
            text-align: center;
            font-size: 13px;
            color: var(--text-light);
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="logo-wrapper">
            <div class="logo-ring">
                <div class="logo">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
            </div>
        </div>

        <h2>College Login</h2>
        <p class="subtitle">Access your college dashboard</p>

        <form method="POST" action="{{ route('college.do.login') }}">
            @csrf

            <div class="form-group">
                <label>College Email</label>
                <input type="email" name="email" placeholder="college@email.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="login-btn">Login</button>

        </form>

        <div class="footer-text">
            Need help? Contact administrator
        </div>

    </div>

</body>

</html>
