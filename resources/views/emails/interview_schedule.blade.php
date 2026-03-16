<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Guidance Interview Schedule</title>
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
                            <p style="color:#e0f0ff; font-size:13px; margin:4px 0 0 0;">Guidance Office</p>
                            <p style="color:#e0f0ff; font-size:12px; margin:0;">Brgy. Navarro, General Trias, Cavite</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; background-color:#ffffff; color:#333;">
                            <h2 style="color:#1c6abb; text-align:center; margin-top:0;">Guidance Interview Schedule</h2>

                            <p>Hi <strong style="color:#000;">{{ $application->fullName() }}</strong>,</p>
                            <p>You passed the entrance examination and your admission record has been forwarded to the Guidance Office. Your interview is scheduled on <strong>{{ $application->interview_date?->format('F d, Y') }}</strong>.</p>

                            <div style="background:#f7fbff; border:1px solid #d9e7f5; border-radius:10px; padding:16px; margin:18px 0;">
                                <div style="font-size:13px; color:#4a5a6a; font-weight:600; margin-bottom:8px;">Before the Interview</div>
                                <p style="font-size:14px; color:#485867; margin:0;">Please complete the guidance form before your interview schedule.</p>
                            </div>

                            <div style="text-align:center; margin:30px 0;">
                                <a href="{{ $application->interviewFormUrl() }}" style="background:#1c6abb; color:#fff; padding:12px 28px; border-radius:6px; text-decoration:none; font-weight:600; font-size:16px;">Complete Guidance Form</a>
                            </div>

                            @if($application->guidance_remarks)
                            <div style="background:#fffbea; border:1px solid #f5db9f; border-radius:10px; padding:16px; margin:18px 0;">
                                <div style="font-size:13px; color:#7a5b1f; font-weight:600; margin-bottom:8px;">Guidance Notes</div>
                                <p style="font-size:14px; color:#5f4b22; margin:0;">{{ $application->guidance_remarks }}</p>
                            </div>
                            @endif

                            <p style="font-size:13px; color:#555;">If the button does not work, copy and open this link in your browser:</p>
                            <p style="font-size:13px; color:#1c6abb; word-break:break-all;">{{ $application->interviewFormUrl() }}</p>

                            <p style="margin-top:30px; text-align:center; font-size:12px; color:#555; background:#f8f9fa; padding:10px; border-radius:6px;"><strong>Note:</strong> This is an automated message. Please do not reply to this email as responses are not monitored.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>