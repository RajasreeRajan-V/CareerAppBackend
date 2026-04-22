<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>College Registration – CareerApp</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@500&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: #ECEFE9;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
  }

  .card {
    background: #fff;
    border-radius: 20px;
    max-width: 460px;
    width: 100%;
    overflow: hidden;
    box-shadow: 0 2px 40px rgba(0,0,0,0.08);
  }

  /* Header */
  .header {
    background: #1C3130;
    padding: 32px 32px 28px;
    position: relative;
    overflow: hidden;
  }

  .header::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
  }

  .header::after {
    content: '';
    position: absolute;
    bottom: -20px; left: 60px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
  }

  .badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 600;
    color: rgba(255,255,255,0.7);
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 16px;
  }

  .badge-dot {
    width: 6px; height: 6px;
    background: #5CC8A0;
    border-radius: 50%;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
  }

  .header h1 {
    font-size: 22px;
    font-weight: 600;
    color: #fff;
    line-height: 1.3;
  }

  .header h1 span { color: #5CC8A0; }

  .header p {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-top: 6px;
  }

  /* Body */
  .body { padding: 28px 32px; }

  .intro {
    font-size: 13.5px;
    color: #6B7280;
    line-height: 1.65;
    margin-bottom: 24px;
  }

  /* Field */
  .field { margin-bottom: 14px; }

  .field-label {
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: #9CA3AF;
    margin-bottom: 6px;
  }

  .field-value {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: 14px;
    color: #1F2937;
    font-weight: 500;
  }

  /* Password field */
  .password-field { margin-bottom: 16px; }

  .password-value {
    background: #F0FAF5;
    border: 1px solid #A7DFC4;
    border-radius: 10px;
    padding: 14px 18px;
    font-family: 'DM Mono', monospace;
    font-size: 20px;
    font-weight: 500;
    color: #1C3130;
    letter-spacing: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .copy-btn {
    font-family: 'DM Sans', sans-serif;
    font-size: 10px;
    letter-spacing: 0;
    color: #5CC8A0;
    font-weight: 600;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
  }

  /* Alert */
  .alert {
    display: flex;
    gap: 10px;
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 22px;
  }

  .alert-icon { font-size: 14px; flex-shrink: 0; margin-top: 1px; }

  .alert p { font-size: 12.5px; color: #92400E; line-height: 1.5; }

  /* Portal */
  .portal {
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .portal-label {
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: #9CA3AF;
    margin-bottom: 4px;
  }

  .portal-url {
    font-size: 13px;
    color: #1C3130;
    font-weight: 500;
    text-decoration: none;
    word-break: break-all;
  }

  .btn {
    background: #1C3130;
    color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 12.5px;
    font-weight: 600;
    padding: 9px 18px;
    border-radius: 8px;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
  }

  /* Footer */
  .footer {
    padding: 14px 32px;
    background: #F9FAFB;
    border-top: 1px solid #E5E7EB;
    font-size: 11.5px;
    color: #9CA3AF;
    text-align: center;
  }

  .footer strong { color: #1C3130; }
</style>
</head>
<body>
<div class="card">

  {{-- Header --}}
  <div class="header">
    <div class="badge"><span class="badge-dot"></span> Registration Successful</div>
    <h1>Welcome, <span>{{ $collegeName }}!</span></h1>
    <p>Your college account is ready to use</p>
  </div>

  {{-- Body --}}
  <div class="body">

    <p class="intro">
      Your college has been registered on CareerApp. Use the credentials below to log in for the first time.
    </p>

    {{-- Email --}}
    <div class="field">
      <div class="field-label">Login Email</div>
      <div class="field-value">{{ $email }}</div>
    </div>

    {{-- Password --}}
    <div class="password-field">
      <div class="field-label">Temporary Password</div>
      <div class="password-value">
        <span id="pwd">{{ $plainPassword }}</span>
        <button class="copy-btn" onclick="
          navigator.clipboard.writeText(document.getElementById('pwd').innerText);
          this.innerText='COPIED!';
          setTimeout(()=>this.innerText='COPY', 2000);
        ">COPY</button>
      </div>
    </div>

    {{-- Warning --}}
    <div class="alert">
      <span class="alert-icon">⚠️</span>
      <p>Change your password immediately after your first login to keep your account secure.</p>
    </div>

    {{-- Portal --}}
    <div class="portal">
      <div>
        <div class="portal-label">College Portal</div>
        <a href="{{ $loginUrl }}" class="portal-url">{{ $loginUrl }}</a>
      </div>
      <a href="{{ $loginUrl }}" class="btn">Login →</a>
    </div>

  </div>

  {{-- Footer --}}
  <div class="footer">
    &copy; {{ date('Y') }} <strong>CareerApp</strong> &nbsp;·&nbsp; Contact support if you didn't request this
  </div>

</div>
</body>
</html>