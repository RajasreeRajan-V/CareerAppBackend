<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Careers | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

        .logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo img {
            max-width: 190px;
        }

        h2 {
            text-align: center;
            color: var(--text-dark);
            font-size: 22px;
            margin-bottom: 6px;
        }

        p.subtitle {
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
            transition: border 0.3s, box-shadow 0.3s;
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
            transition: background 0.3s, transform 0.15s;
        }

        .login-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
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

        .footer-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
            }
        }

        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 28px;
        }

        /* LOGO RING */
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

        /* Outer Ring */
        .logo-ring::before {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            border: 4px solid rgba(48, 96, 96, 0.35);
        }

        /* Inner Ring */
        .logo-ring::after {
            content: "";
            position: absolute;
            inset: 2px;
            border-radius: 50%;
            border: 2px solid rgba(79, 163, 163, 0.8);
            filter: blur(0.5px);
        }

        /* LOGO INSIDE RING */
        .logo {
            width: 120px;
            height: 120px;
            background: #f8fbfb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(48, 96, 96, 0.18);
        }

        /* LOGO IMAGE */
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="logo-wrapper">
            <div class="logo-ring">
                <div class="logo">
                    <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Careers Logo">
                </div>
            </div>
        </div>


        <h2>Sign in to Careers</h2>
        <p class="subtitle">Access your dashboard securely</p>

       <form method="POST" action="{{ route('admin.do.login') }}">
         @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="name@example.com" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" placeholder="••••••••" name="password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="footer-text">
            Forgot your password? <a href="#">Reset here</a>
        </div>
    </div>

</body>

</html>