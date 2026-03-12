<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Confirmed – SCC</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            color: #2d3748;
        }

        .wrapper {
            max-width: 600px;
            margin: 32px auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #0d1f3c 0%, #1a5276 100%);
            padding: 32px 40px;
            text-align: center;
        }

        .header img {
            height: 60px;
            margin-bottom: 12px;
        }

        .header h1 {
            color: #fff;
            font-size: 20px;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.75);
            margin: 6px 0 0;
            font-size: 13px;
        }

        .body {
            padding: 36px 40px;
        }

        .greeting {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .intro {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .confirmed-banner {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .confirmed-banner .icon {
            font-size: 28px;
        }

        .confirmed-banner .text {
            font-size: 14px;
            font-weight: bold;
            color: #166534;
        }

        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 24px;
        }

        .info-card h3 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin: 0 0 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
        }

        .schedule-highlight {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1.5px solid #93c5fd;
            border-radius: 10px;
            padding: 18px 24px;
            margin-bottom: 24px;
            text-align: center;
        }

        .schedule-highlight .day {
            font-size: 22px;
            font-weight: 800;
            color: #1e3a5f;
            margin-bottom: 4px;
        }

        .schedule-highlight .time {
            font-size: 18px;
            font-weight: 700;
            color: #1d4ed8;
        }

        .schedule-highlight .label {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
        }

        .checklist {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 18px 24px;
            margin-bottom: 24px;
        }

        .checklist h3 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #92400e;
            margin: 0 0 12px;
        }

        .checklist ul {
            margin: 0;
            padding-left: 18px;
            font-size: 14px;
            color: #4a5568;
            line-height: 1.8;
        }

        .login-card {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 10px;
            padding: 18px 24px;
            margin-bottom: 24px;
        }

        .login-card h3 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #166534;
            margin: 0 0 12px;
        }

        .login-card .cred-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            padding: 5px 0;
            border-bottom: 1px solid #dcfce7;
        }

        .login-card .cred-row:last-child {
            border-bottom: none;
        }

        .login-card .cred-label {
            color: #64748b;
        }

        .login-card .cred-value {
            font-weight: 700;
            font-family: monospace;
            color: #166534;
        }

        .warning {
            font-size: 12px;
            color: #92400e;
            background: #fffbeb;
            border-radius: 6px;
            padding: 8px 12px;
            margin-top: 10px;
        }

        .footer {
            background: #f8fafc;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            font-size: 12px;
            color: #94a3b8;
            margin: 4px 0;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        <!-- Header -->
        <div class="header">
            <h1>Samuel Christian College</h1>
            <p>Admission Office — Entrance Exam Confirmation</p>
        </div>

        <!-- Body -->
        <div class="body">
            <div class="greeting">Hello, {{ $application->fullName() }}!</div>
            <div class="intro">
                We are pleased to inform you that your application to Samuel Christian College has been
                reviewed and <strong>confirmed</strong>. Please find your entrance exam details below.
            </div>

            <!-- Confirmed Banner -->
            <div class="confirmed-banner">
                <div class="icon">✅</div>
                <div class="text">Your application has been confirmed!</div>
            </div>

            <!-- Application Info -->
            <div class="info-card">
                <h3>Application Details</h3>
                <div class="info-row">
                    <span class="info-label">Application ID</span>
                    <span class="info-value">{{ $application->app_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Student ID</span>
                    <span class="info-value">{{ $student->student_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">{{ $application->fullName() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Program</span>
                    <span class="info-value">{{ $application->program->name ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Exam Schedule Highlight -->
            @php
            $sched = $application->examSchedule ?? null;
            if ($sched) {
            $examDay = $sched->exam_date->format('l');
            $examDate = $sched->exam_date->format('F j, Y');
            $examTime = $sched->time_slot === '9am' ? '9:00 AM' : '1:00 PM';
            $sessionLabel = $sched->time_slot === '9am' ? 'Morning Session' : 'Afternoon Session';
            } else {
            $is9am = $application->exam_schedule === 'saturday_9am';
            $examDay = 'Saturday';
            $examDate = null;
            $examTime = $is9am ? '9:00 AM' : '1:00 PM';
            $sessionLabel = $is9am ? 'Morning Session' : 'Afternoon Session';
            }
            @endphp
            <div class="schedule-highlight">
                <div class="label" style="margin-bottom:6px; font-size:12px; color:#64748b; text-transform:uppercase; letter-spacing:1px;">Your Entrance Exam Schedule</div>
                <div class="day">{{ $examDay }}</div>
                @if($examDate)
                <div style="font-size:15px; font-weight:600; color:#1e3a5f; margin-bottom:4px;">{{ $examDate }}</div>
                @endif
                <div class="time">{{ $examTime }}</div>
                <div class="label">{{ $sessionLabel }} &nbsp;·&nbsp; Venue: TBA</div>
            </div>

            <!-- What to Bring -->
            <div class="checklist">
                <h3>📋 What to Bring</h3>
                <ul>
                    <li>Valid school ID or any government-issued photo ID (PSA, passport, etc.)</li>
                    <li>A printed or digital copy of this email (for reference)</li>
                    <li>Your Application ID: <strong>{{ $application->app_id }}</strong></li>
                    <li>Pencils, ballpen, and eraser</li>
                    <li>Arrive at least <strong>15 minutes before</strong> your scheduled time</li>
                </ul>
            </div>

            <!-- Login Credentials -->
            <div class="login-card">
                <h3>🔐 Your Portal Login Credentials</h3>
                <div class="cred-row">
                    <span class="cred-label">Email</span>
                    <span class="cred-value">{{ $application->email }}</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Password</span>
                    <span class="cred-value">password</span>
                </div>
                <div class="warning">
                    ⚠️ Please log in at your earliest convenience and change your password immediately.
                </div>
            </div>

            <p style="font-size:14px; color:#4a5568; line-height:1.6;">
                If you have any questions, please don't hesitate to contact the Registrar's Office.
                We look forward to seeing you on exam day!
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Samuel Christian College</strong> — Registrar's Office</p>
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} SCC Portal. All rights reserved.</p>
        </div>
    </div>
</body>

</html>