<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guidance Interview Schedule</title>
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
            background: #0d1f3c;
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

        .button {
            display: inline-block;
            padding: 12px 18px;
            background: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
        }

        .muted {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <h2 style="margin:0;">Samuel Christian College</h2>
            <p style="margin:8px 0 0; color: rgba(255,255,255,0.78);">Guidance Office Interview Scheduling</p>
        </div>
        <div class="body">
            <p>Hello {{ $application->fullName() }},</p>
            <p class="muted">You passed the entrance examination and your admission record has been forwarded to the Guidance Office. Your interview has been scheduled on <strong>{{ $application->interview_date?->format('F d, Y') }}</strong>.</p>
            <div class="card">
                <div style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 10px;">Before the Interview</div>
                <p class="muted" style="margin:0;">Please open the form below and complete the remaining information. The form already includes the main details you submitted in your original admission application.</p>
            </div>
            <p style="margin: 24px 0;"><a href="{{ $application->interviewFormUrl() }}" class="button">Complete Guidance Form</a></p>
            @if($application->guidance_remarks)
            <div class="card">
                <div style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 10px;">Guidance Notes</div>
                <p class="muted" style="margin:0;">{{ $application->guidance_remarks }}</p>
            </div>
            @endif
            <p class="muted">If the button does not work, copy and open this link in your browser:</p>
            <p class="muted">{{ $application->interviewFormUrl() }}</p>
        </div>
    </div>
</body>

</html>