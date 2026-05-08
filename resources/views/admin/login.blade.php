<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
            margin: 0;
            padding: 0;
        }

        body {
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
            padding: 36px 35px 32px;
            border-radius: 16px;
            box-shadow:
                0 25px 50px rgba(0, 0, 0, 0.2),
                0 10px 20px rgba(0, 0, 0, 0.12);
        }

        /* ── TOGGLE SWITCH ── */
        .switch-wrapper {
            display: flex;
            background: #f0f4f4;
            border-radius: 40px;
            padding: 4px;
            margin-bottom: 28px;
            position: relative;
            border: 1px solid #d5e3e3;
        }

        .switch-pill {
            position: absolute;
            top: 4px;
            left: 4px;
            width: calc(50% - 4px);
            height: calc(100% - 8px);
            background: var(--primary);
            border-radius: 36px;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 10px rgba(48, 96, 96, 0.35);
        }

        .switch-pill.college {
            transform: translateX(100%);
        }

        .switch-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 12px;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 36px;
            cursor: pointer;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            color: var(--text-light);
            transition: color 0.3s;
        }

        .switch-btn.active {
            color: var(--white);
        }

        /* ── LOGO RING ── */
        .logo-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .logo-ring {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .logo-ring:hover {
            transform: scale(1.04);
        }

        .logo-ring::before {
            content: "";
            position: absolute;
            inset: 7px;
            border-radius: 50%;
            border: 3px solid rgba(48, 96, 96, 0.3);
        }

        .logo-ring::after {
            content: "";
            position: absolute;
            inset: 2px;
            border-radius: 50%;
            border: 2px solid rgba(79, 163, 163, 0.75);
        }

        .logo-inner {
            width: 100px;
            height: 100px;
            background: rgba(48, 96, 96, 0.08);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-inner i {
            font-size: 48px;
            color: var(--primary);
        }

        .logo-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* ── HEADINGS ── */
        h2 {
            text-align: center;
            color: var(--text-dark);
            font-size: 21px;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: var(--text-light);
            font-size: 13.5px;
            margin-bottom: 26px;
        }

        /* ── FORM PANELS ── */
        .form-panel {
            display: none;
            animation: fadeUp 0.3s ease;
        }

        .form-panel.active {
            display: block;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 11px 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 14px;
            transition: border 0.25s, box-shadow 0.25s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(48, 96, 96, 0.14);
        }

        .login-btn {
            width: 100%;
            margin-top: 8px;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 13px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.25s, transform 0.15s;
        }

        .login-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .footer-text {
            margin-top: 20px;
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
                padding: 28px 22px 26px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        {{-- ── TOGGLE SWITCH ── --}}
        <div class="switch-wrapper">
          <div class="switch-pill {{ session('login_type') === 'college' ? 'college' : '' }}" id="pill"></div>

            <button type="button" class="switch-btn {{ session('login_type') === 'college' ? '' : 'active' }}"
                id="btn-admin" onclick="switchTo('admin')">
                <i class="fa-solid fa-shield-halved"></i> Admin
            </button>

            <button type="button" class="switch-btn {{ session('login_type') === 'college' ? 'active' : '' }}"
                id="btn-college" onclick="switchTo('college')">
                <i class="fa-solid fa-building-columns"></i> College
            </button>
        </div>

        {{-- ── LOGO ── --}}
        <div class="logo-wrapper">
            <div class="logo-ring">
                <div class="logo-inner" id="logo-icon">
                    {{-- Default: Admin shows the careers logo image --}}
                    <img src="{{ asset('img/careers_logo_teal.png') }}" alt="Careers Logo" id="logo-img">
                    <i class="fa-solid fa-building-columns" id="logo-fa" style="display:none;"></i>
                </div>
            </div>
        </div>

        {{-- ── TITLE ── --}}
        <h2 id="main-title">Sign in to Careers</h2>
        <p class="subtitle" id="main-subtitle">Access your admin dashboard securely</p>

        {{-- ── ADMIN FORM ── --}}
        <div class="form-panel {{ session('login_type') === 'college' ? '' : 'active' }}" id="panel-admin">
            <form method="POST" action="{{ route('admin.do.login') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="name@example.com" value="{{ old('email') }}"
                        required autofocus>
                    @error('email')
                        <span style="color:red;font-size:12px;">{{ $message }}</span>
                    @enderror
                </div>
                <input type="hidden" name="login_type" value="admin">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <span style="color:red;font-size:12px;">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="footer-text">
                Forgot your password? <a href="#">Reset here</a>
            </div>
        </div>

        {{-- ── COLLEGE FORM ── --}}
        <div class="form-panel {{ session('login_type') === 'college' ? 'active' : '' }}" id="panel-college">
            <form method="POST" action="{{ route('college.do.login') }}">
                @csrf
                <div class="form-group">
                    <label>College Email</label>
                    <input type="email" name="email" placeholder="college@email.com" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <span style="color:red;font-size:12px;">{{ $message }}</span>
                    @enderror
                </div>
                <input type="hidden" name="login_type" value="college">ss
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <span style="color:red;font-size:12px;">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="footer-text">
                Need help? Contact administrator
            </div>
        </div>

    </div>
   
    <script>
        function switchTo(type) {
            const pill = document.getElementById('pill');
            const btnAdmin = document.getElementById('btn-admin');
            const btnCollege = document.getElementById('btn-college');
            const panelAdmin = document.getElementById('panel-admin');
            const panelCollege = document.getElementById('panel-college');
            const logoImg = document.getElementById('logo-img');
            const logoFa = document.getElementById('logo-fa');
            const title = document.getElementById('main-title');
            const subtitle = document.getElementById('main-subtitle');

            if (type === 'college') {
                pill.classList.add('college');
                btnAdmin.classList.remove('active');
                btnCollege.classList.add('active');
                panelAdmin.classList.remove('active');
                panelCollege.classList.add('active');
                logoImg.style.display = 'none';
                logoFa.style.display = 'block';
                title.textContent = 'College Login';
                subtitle.textContent = 'Access your college dashboard';
            } else {
                pill.classList.remove('college');
                btnAdmin.classList.add('active');
                btnCollege.classList.remove('active');
                panelCollege.classList.remove('active');
                panelAdmin.classList.add('active');
                logoImg.style.display = 'block';
                logoFa.style.display = 'none';
                title.textContent = 'Sign in to Careers';
                subtitle.textContent = 'Access your admin dashboard securely';
            }
        }
    </script>

</body>

</html>
