<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Career Guidance Session</title>
</head>

<body style="margin:0;padding:0;background:#eef2f7;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
<tr>
<td align="center">

<table width="620" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 20px rgba(0,0,0,0.08);">

<!-- HEADER -->
<tr>
<td style="background:#2d89ff;padding:30px;text-align:center;color:white;">
<h2 style="margin:0;font-size:22px;">Registration Confirmed</h2>
<p style="margin:8px 0 0 0;font-size:14px;opacity:0.9;">
You are successfully registered for the session
</p>
</td>
</tr>

<!-- EVENT NAME -->
<tr>
<td style="padding:30px 40px 10px 40px;text-align:center;">
<h2 style="margin:0;color:#1f2937;font-size:24px;">
{{ $banner->name }}
</h2>
</td>
</tr>

<!-- GREETING -->
<tr>
<td style="padding:10px 40px 10px 40px;text-align:center;">
<p style="font-size:15px;color:#6b7280;margin:0;">
Hello <strong>{{ $user['name'] }}</strong>,  
thank you for registering for this career guidance session.
</p>
</td>
</tr>

<!-- INSTRUCTOR SECTION -->
<tr>
<td align="center" style="padding:25px 40px;">

<img src="{{ asset('storage/'.$banner->image) }}"
style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid #ffffff;box-shadow:0 4px 12px rgba(0,0,0,0.15);">

<h3 style="margin:12px 0 0 0;color:#1f2937;">
{{ $banner->instructor_name }}
</h3>

<p style="margin:5px 0 0 0;color:#6b7280;font-size:14px;">
Session Instructor
</p>

</td>
</tr>

<!-- SESSION DETAILS -->
<tr>
<td style="padding:10px 40px 20px 40px;">

<table width="100%" style="border:1px solid #eef0f4;border-radius:8px;">

<tr>
<td style="padding:15px;border-bottom:1px solid #eef0f4;font-size:15px;">
<strong>Date:</strong> {{ $banner->event_date }}
</td>
</tr>

<tr>
<td style="padding:15px;font-size:15px;">
<strong>Time:</strong> {{ $banner->start_time }} - {{ $banner->end_time }}
</td>
</tr>

</table>

</td>
</tr>

<!-- DESCRIPTION -->
<tr>
<td style="padding:10px 40px 30px 40px;">

<h3 style="margin-bottom:10px;color:#1f2937;font-size:18px;">
About this session
</h3>

<p style="margin:0;color:#4b5563;font-size:14px;line-height:1.6;">
{{ $banner->description }}
</p>

</td>
</tr>

<!-- JOIN BUTTON -->
<tr>
<td align="center" style="padding-bottom:35px;">

<a href="https://meet.google.com/{{ $banner->google_meet_link }}"
style="background:#2d89ff;
color:#ffffff;
padding:14px 36px;
text-decoration:none;
font-size:16px;
font-weight:bold;
border-radius:6px;
display:inline-block;
box-shadow:0 4px 10px rgba(45,137,255,0.3);">

Join Live Session

</a>

<p style="margin-top:12px;font-size:13px;color:#6b7280;">
Click the button above to join the session at the scheduled time.
</p>

</td>
</tr>

<!-- FOOTER -->
<tr>
<td style="background:#f7f9fc;text-align:center;padding:20px;font-size:12px;color:#6b7280;">

<p style="margin:0;">Career Guidance Platform</p>
<p style="margin:6px 0 0 0;">Helping students choose the right career path.</p>

</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>