<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Application - SCC Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a3a5c 0%, #2c7be5 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }
        .track-card {
            max-width: 520px; width: 100%;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.25);
        }
        .track-header {
            background: #1a3a5c;
            color: white;
            padding: 2rem;
            border-radius: 1rem 1rem 0 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="track-card bg-white">
        <div class="track-header">
            <i class="bi bi-search fs-1"></i>
            <h4 class="mt-2 mb-0">Track Your Application</h4>
            <small class="text-white-50">Enter your email to check status</small>
        </div>

        <div class="p-4">
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
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               value="{{ old('email') }}" required autofocus placeholder="your@email.com">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </form>

            {{-- Results --}}
            @if(isset($application))
                <hr>
                <div class="border rounded p-3">
                    <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-1"></i> Application Details</h6>
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
                                        default    => 'warning',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }} fs-6">
                                    <i class="bi bi-{{ $application->status === 'approved' ? 'check-circle' : ($application->status === 'rejected' ? 'x-circle' : 'hourglass-split') }} me-1"></i>
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
                            <i class="bi bi-check-circle me-1"></i>
                            Your application has been approved! Please check your email for login credentials or visit the Registrar's Office.
                        </div>
                    @elseif($application->isRejected())
                        <div class="alert alert-danger mt-3 mb-0 small">
                            <i class="bi bi-x-circle me-1"></i>
                            Unfortunately, your application was not approved. Please contact the Registrar's Office for more information.
                        </div>
                    @else
                        <div class="alert alert-info mt-3 mb-0 small">
                            <i class="bi bi-clock me-1"></i>
                            Your application is still under review. Please check back later.
                        </div>
                    @endif
                </div>
            @elseif(request()->isMethod('POST'))
                <hr>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    No application found with that email address.
                </div>
            @endif
        </div>

        <div class="text-center pb-3">
            <a href="{{ route('admission.apply') }}" class="text-decoration-none small">
                <i class="bi bi-pencil-square me-1"></i> Apply for Admission
            </a>
            <span class="text-muted mx-2">|</span>
            <a href="{{ route('login') }}" class="text-decoration-none small">
                <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
            </a>
        </div>
        <div class="text-center pb-3">
            <small class="text-muted">&copy; {{ date('Y') }} SCC Portal. All rights reserved.</small>
        </div>
    </div>
</body>
</html>
