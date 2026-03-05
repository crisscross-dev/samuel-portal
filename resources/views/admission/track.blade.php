<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Application - SCC Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/scc_logo.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/index.css'])

    <style>
        body {
            background-image: url('{{ asset("images/background.png") }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <div class="container py-3">

        <!-- Topbar -->
        <div class="topbar">
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-right-to-bracket"></i>
                Login
            </a>
        </div>

        <!-- Header -->
        <div class="header py-3">
            <div class="clinic-logo">
                <img src="{{ asset('images/scc_logo.png') }}" alt="SCC Logo" />
            </div>
            <h1>Samuel Christian College</h1>
            <h2>Application Tracker</h2>
        </div>

        <!-- Track Section -->
        <div class="patient-section" style="max-width: 560px; margin: 0 auto 1.5rem;">
            <h2 class="section-title"><i class="fas fa-magnifying-glass me-2"></i>Track Your Application</h2>
            <p class="section-subtitle mb-4">Enter your email address to check your application status.</p>

            @if($errors->any())
            <div class="alert alert-danger py-2">
                @foreach($errors->all() as $error)
                <div><small>{{ $error }}</small></div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('admission.track.search') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email') }}" required autofocus placeholder="your@email.com">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="fas fa-magnifying-glass me-1"></i> Search
                </button>
            </form>

            {{-- Results --}}
            @if(isset($application))
            <hr>
            <div class="border rounded p-3">
                <h6 class="fw-bold mb-3"><i class="fas fa-file-lines me-1"></i> Application Details</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:40%">Name</td>
                        <td class="fw-semibold">{{ $application->fullName() }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Program</td>
                        <td>{{ $application->program->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Year Level</td>
                        <td>{{ $application->year_level }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date Applied</td>
                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @php
                            $badge = match($application->status) {
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'warning',
                            };
                            @endphp
                            <span class="badge bg-{{ $badge }} fs-6">
                                <i class="fas fa-{{ $application->status === 'approved' ? 'circle-check' : ($application->status === 'rejected' ? 'circle-xmark' : 'hourglass-half') }} me-1"></i>
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                    </tr>
                    @if($application->remarks)
                    <tr>
                        <td class="text-muted">Remarks</td>
                        <td>{{ $application->remarks }}</td>
                    </tr>
                    @endif
                    @if($application->reviewed_at)
                    <tr>
                        <td class="text-muted">Reviewed On</td>
                        <td>{{ $application->reviewed_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @endif
                </table>

                @if($application->isApproved())
                <div class="alert alert-success mt-3 mb-0 small">
                    <i class="fas fa-circle-check me-1"></i>
                    Your application has been approved! Please check your email for login credentials or visit the Registrar's Office.
                </div>
                @elseif($application->isRejected())
                <div class="alert alert-danger mt-3 mb-0 small">
                    <i class="fas fa-circle-xmark me-1"></i>
                    Unfortunately, your application was not approved. Please contact the Registrar's Office for more information.
                </div>
                @else
                <div class="alert alert-info mt-3 mb-0 small">
                    <i class="fas fa-clock me-1"></i>
                    Your application is still under review. Please check back later.
                </div>
                @endif
            </div>
            @elseif(request()->isMethod('POST'))
            <hr>
            <div class="alert alert-warning mb-0">
                <i class="fas fa-triangle-exclamation me-1"></i>
                No application found with that email address.
            </div>
            @endif
        </div>

        <div class="text-center pb-3">
            <a href="{{ route('admission.apply') }}" class="text-decoration-none small" style="color: rgba(255,255,255,0.9);">
                <i class="fas fa-pen-to-square me-1"></i> Apply for Admission
            </a>
            <span class="mx-2" style="color: rgba(255,255,255,0.5);">|</span>
            <a href="{{ route('login') }}" class="text-decoration-none small" style="color: rgba(255,255,255,0.9);">
                <i class="fas fa-right-to-bracket me-1"></i> Sign In
            </a>
        </div>
        <div class="text-center pb-3">
            <small style="color: rgba(255,255,255,0.6);">&copy; {{ date('Y') }} SCC Portal. All rights reserved.</small>
        </div>
    </div>
</body>

</html>