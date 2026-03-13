<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - SCC Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a3a5c 0%, #2c7be5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-card {
            max-width: 520px;
            width: 100%;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.25);
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="success-card bg-white p-5">
        <div class="mb-3">
            <span class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width:80px;height:80px;">
                <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
            </span>
        </div>

        <h4 class="fw-bold text-dark mb-2">
            @if(session('payment_submitted'))
            Application & Payment Submitted!
            @else
            Application Submitted!
            @endif
        </h4>
        <p class="text-muted mb-3">
            @if(session('payment_submitted'))
            Thank you! Your application and payment receipt have been received.
            The Registrar will review your submission and send a confirmation email once your exam slot is confirmed.
            @else
            Thank you for applying to SCC. Your application has been received and is now under review.
            @endif
        </p>

        @if(session('app_id'))
        <div class="alert alert-secondary py-2 mb-3 text-center">
            <small class="text-muted d-block">Your Application ID</small>
            <strong class="fs-6 text-dark" style="letter-spacing:1px;">{{ session('app_id') }}</strong>
            <small class="d-block text-muted mt-1">Save this ID to track your application status.</small>
        </div>
        @endif

        @if(session('exam_schedule'))
        <div class="alert alert-success py-2 mb-3 text-start small">
            <i class="bi bi-calendar-check me-1"></i>
            <strong>Entrance Exam Schedule:</strong>
            {{ session('exam_schedule') === 'saturday_9am' ? 'Saturday – 9:00 AM (Morning)' : 'Saturday – 1:00 PM (Afternoon)' }}
        </div>
        @endif

        <div class="alert alert-info text-start small">
            <i class="bi bi-info-circle me-1"></i>
            <strong>What happens next?</strong>
            <ul class="mb-0 mt-1">
                @if(session('payment_submitted'))
                <li>The Registrar's Office will verify your payment receipt.</li>
                <li>Once confirmed, the Registrar can clear you to take the entrance examination.</li>
                <li>Attend the entrance exam on your chosen schedule.</li>
                <li>If you pass, your record will be forwarded to the Guidance Office for interview scheduling.</li>
                @else
                <li>Attend the entrance exam on your chosen schedule.</li>
                <li>The Registrar's Office will review your application and determine if you may take the exam.</li>
                <li>If you pass the exam, the Guidance Office will schedule your interview.</li>
                <li>You will receive an email with a form link to complete the remaining admission details.</li>
                @endif
            </ul>
        </div>

        <div class="d-flex flex-column gap-2 mt-4">
            <a href="{{ route('admission.track') }}" class="btn btn-primary fw-semibold">
                <i class="bi bi-search me-1"></i> Track Your Application
            </a>
            <a href="{{ route('admission.apply') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Submit Another Application
            </a>
            <a href="{{ route('login') }}" class="btn btn-link text-muted">
                Already have an account? Sign In
            </a>
        </div>

        <div class="mt-4">
            <small class="text-muted">&copy; {{ date('Y') }} SCC Portal. All rights reserved.</small>
        </div>
    </div>
</body>

</html>