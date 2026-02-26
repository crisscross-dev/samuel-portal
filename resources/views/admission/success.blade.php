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
            display: flex; align-items: center; justify-content: center;
        }
        .success-card {
            max-width: 520px; width: 100%;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.25);
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

        <h4 class="fw-bold text-dark mb-2">Application Submitted!</h4>
        <p class="text-muted mb-4">
            Thank you for applying to SCC. Your application has been received and is now under review.
            You will be notified once a decision has been made.
        </p>

        <div class="alert alert-info text-start small">
            <i class="bi bi-info-circle me-1"></i>
            <strong>What happens next?</strong>
            <ul class="mb-0 mt-1">
                <li>The Registrar's Office will review your application.</li>
                <li>If approved, a student account will be created for you.</li>
                <li>You will receive your Student ID and login credentials.</li>
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
