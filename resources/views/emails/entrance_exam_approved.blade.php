<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrance Examination Approval</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            color: #1f2937;
        }

        .wrapper {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #0d1f3c 0%, #1f4f8a 100%);
            color: #ffffff;
            padding: 28px 32px;
        }

        .body {
            padding: 32px;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .value {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .muted {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <h2 style="margin:0;">Samuel Christian College</h2>
            <p style="margin:8px 0 0; color: rgba(255,255,255,0.78);">Admission Office</p>
        </div>

        <div class="body">
            <p>Hello {{ $application->fullName() }},</p>
            <p class="muted">
                Your application has been approved for the entrance examination. Please keep your application details
                and attend the exam on your assigned schedule.
            </p>

            <div class="card">
                <div class="label">Application ID</div>
                <div class="value">{{ $application->app_id ?: 'N/A' }}</div>
            </div>

            <div class="card">
                <div class="label">Program Applied</div>
                <div class="value">{{ $application->program->name ?? 'N/A' }}</div>
            </div>

            <div class="card">
                <div class="label">Entrance Examination Schedule</div>
                <div class="value">{{ $application->examLabel() }}</div>
                <div style="margin-top: 10px;"><span class="badge">Approved for Examination</span></div>
            </div>

            <div class="card">
                <div class="label">Next Step</div>
                <p class="muted" style="margin:0;">
                    Present this email or your application ID on exam day. After the examination, the Registrar will
                    record your result and, if you pass, your record will be forwarded to the Guidance Office.
                </p>
            </div>
        </div>
    </div>
</body>

</html>