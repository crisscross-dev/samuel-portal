<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admission Confirmed</title>
</head>

<body style="margin:0; padding:0; background-color:#eaf3fb; font-family:'Segoe UI', Arial, sans-serif; line-height:1.6;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f8f9fa; padding:30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="background-color:#ffffff; border-radius:16px; border:5px solid #1c6abb; box-shadow:0 4px 10px rgba(0,0,0,0.1); overflow:hidden;">
                    <tr>
                        <td align="center" style="background-color:#1c6abb; border-top-left-radius:12px; border-top-right-radius:12px; padding:20px 10px;">
                            <img src="{{ $message->embed(public_path('images/scc_logo.png')) }}" alt="Samuel Christian College Logo" style="max-width:80px; border-radius:8px;">
                            <h1 style="color:#ffffff; font-size:20px; margin:10px 0 0 0; font-weight:600;">Samuel Christian College</h1>
                            <p style="color:#e0f0ff; font-size:13px; margin:4px 0 0 0;">Admission Office</p>
                            <p style="color:#e0f0ff; font-size:12px; margin:0;">Brgy. Navarro, General Trias, Cavite</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; background-color:#ffffff; color:#333;">
                            <h2 style="color:#28a745; text-align:center; margin-top:0;">Admission Confirmed</h2>

                            <p>Hi <strong style="color:#000;">{{ $application->fullName() }}</strong>,</p>
                            <p>Your admission process has been completed successfully. Please review your details and exam schedule below.</p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:18px 0; border:1px solid #d9e7f5; border-radius:10px; background:#f7fbff;">
                                <tr>
                                    <td style="padding:14px 16px; border-bottom:1px solid #e7eef7; font-size:14px; color:#4a5a6a;">Application ID</td>
                                    <td style="padding:14px 16px; border-bottom:1px solid #e7eef7; font-size:14px; font-weight:600; color:#1d2b38; text-align:right;">{{ $application->app_id ?: 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 16px; border-bottom:1px solid #e7eef7; font-size:14px; color:#4a5a6a;">Program</td>
                                    <td style="padding:14px 16px; border-bottom:1px solid #e7eef7; font-size:14px; font-weight:600; color:#1d2b38; text-align:right;">{{ $application->program->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 16px; font-size:14px; color:#4a5a6a;">Exam Schedule</td>
                                    <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1d2b38; text-align:right;">{{ $application->examLabel() }}</td>
                                </tr>
                            </table>

                            @if(isset($student))
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:18px 0; border:1px solid #d6f0dd; border-radius:10px; background:#f3fff6;">
                                <tr>
                                    <td style="padding:14px 16px; border-bottom:1px solid #e3f3e7; font-size:14px; color:#4a5a6a;">Student ID</td>
                                    <td style="padding:14px 16px; border-bottom:1px solid #e3f3e7; font-size:14px; font-weight:600; color:#1d2b38; text-align:right;">{{ $student->student_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 16px; font-size:14px; color:#4a5a6a;">Account Email</td>
                                    <td style="padding:14px 16px; font-size:14px; font-weight:600; color:#1d2b38; text-align:right;">{{ $application->email }}</td>
                                </tr>
                            </table>
                            @endif

                            <p style="font-size:13px; color:#555;">Please keep this message for your records and bring your application ID on your scheduled date.</p>

                            <p style="margin-top:30px; text-align:center; font-size:12px; color:#555; background:#f8f9fa; padding:10px; border-radius:6px;"><strong>Note:</strong> This is an automated message. Please do not reply to this email as responses are not monitored.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>