<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Careers Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --primary: #306060;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, sans-serif;
        }

        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(135deg, #306060, #1f4c4c);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: var(--white);
            width: 100%;
            max-width: 400px;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 200px;
        }

        h2 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        label {
            font-size: 13px;
            color: #444;
            display: block;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .login-btn {
            width: 100%;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #254f4f;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 13px;
            color: #777;
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo">
        <img src="{{ asset("img/careers_logo_teal.png") }}" alt="Careers Logo">
    </div>

    <h2>Welcome Back</h2>
    <p>Please login to your account</p>

    <form>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" placeholder="Enter your email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="login-btn">Login</button>
    </form>

    <div class="footer-text">
        Forgot password? <a href="#">Reset</a>
    </div>
</div>

</body>
</html>
