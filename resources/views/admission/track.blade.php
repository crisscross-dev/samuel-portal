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
            <p class="section-subtitle mb-4">Enter your <strong>Application ID</strong> (e.g. APP-2026-00001) or <strong>email address</strong> to check your status.</p>

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
                    <label for="search" class="form-label fw-semibold">Application ID or Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ old('search') }}" required autofocus
                            placeholder="APP-2026-00001 or your@email.com">
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
                    @if($application->app_id)
                    <tr>
                        <td class="text-muted" style="width:40%">Application ID</td>
                        <td><span class="badge bg-dark fw-bold" style="font-size:0.85rem; letter-spacing:1px;">{{ $application->app_id }}</span></td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Name</td>
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
                        <td class="text-muted">Workflow</td>
                        <td>
                            @php
                            $workflowBadge = match($application->workflow_stage) {
                            'exam_approved' => 'bg-info',
                            'guidance_review' => 'bg-primary',
                            'interview_scheduled' => 'bg-warning text-dark',
                            'interview_form_submitted' => 'bg-success',
                            'registrar_requirements' => 'bg-primary',
                            'enrollment' => 'bg-success',
                            'cashier_payment' => 'bg-dark',
                            'archived' => 'bg-secondary',
                            'exam_failed', 'rejected' => 'bg-danger',
                            default => 'bg-secondary',
                            };
                            @endphp
                            <span class="badge {{ $workflowBadge }} fs-6">{{ $application->workflowLabel() }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Exam Result</td>
                        <td>{{ $application->examResultLabel() }}</td>
                    </tr>
                    @if($application->interview_result)
                    <tr>
                        <td class="text-muted">Interview Result</td>
                        <td>{{ $application->interviewResultLabel() }}</td>
                    </tr>
                    @endif
                    @if($application->interview_date)
                    <tr>
                        <td class="text-muted">Interview Date</td>
                        <td>{{ $application->interview_date->format('F d, Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Payment</td>
                        <td>
                            @if($application->isPaymentPaid())
                            <span class="badge bg-success fs-6"><i class="fas fa-circle-check me-1"></i>Paid</span>
                            @elseif($application->hasPaymentSubmitted())
                            <span class="badge bg-primary fs-6"><i class="fas fa-hourglass-half me-1"></i>Under Verification</span>
                            @else
                            <span class="badge bg-warning text-dark fs-6"><i class="fas fa-clock me-1"></i>Pending</span>
                            @endif
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

                {{-- Payment CTA --}}
                @if($application->app_id)
                @if($application->isPaymentPaid())
                <div class="alert alert-success mt-3 mb-2 small">
                    <i class="fas fa-circle-check me-1"></i>
                    Payment verified. Your admission is being processed.
                </div>
                @elseif($application->hasPaymentSubmitted())
                <div class="alert alert-info mt-3 mb-2 small">
                    <i class="fas fa-hourglass-half me-1"></i>
                    Your GCash receipt is under review. We will notify you once verified.
                </div>
                @else
                <div class="alert alert-warning mt-3 mb-2 small">
                    <i class="fas fa-triangle-exclamation me-1"></i>
                    Admission fee (₱200) is required to complete your application.
                </div>
                <a href="{{ route('admission.payment.show', $application->app_id) }}"
                    class="btn btn-warning w-100 fw-semibold">
                    <i class="fas fa-qrcode me-2"></i> Continue Payment — ₱200
                </a>
                @endif
                @endif

                @if($application->isExamApproved())
                <div class="alert alert-success mt-3 mb-0 small">
                    <i class="fas fa-circle-check me-1"></i>
                    You are approved to take the entrance examination. Please attend your assigned exam schedule.
                </div>
                @elseif($application->isForwardedToGuidance())
                <div class="alert alert-primary mt-3 mb-0 small">
                    <i class="fas fa-share me-1"></i>
                    You passed the entrance exam and your record has been forwarded to the Guidance Office.
                </div>
                @elseif($application->hasInterviewScheduled())
                <div class="alert alert-warning mt-3 mb-0 small">
                    <i class="fas fa-calendar-day me-1"></i>
                    Your guidance interview is scheduled on {{ $application->interview_date?->format('F d, Y') }}. Check your email for the form link.
                </div>
                @elseif($application->hasSubmittedInterviewForm())
                <div class="alert alert-success mt-3 mb-0 small">
                    <i class="fas fa-file-circle-check me-1"></i>
                    Your guidance form has been submitted. Please wait for the next admission update.
                </div>
                @elseif($application->isInRegistrarRequirements())
                <div class="alert alert-primary mt-3 mb-0 small">
                    <i class="fas fa-folder-open me-1"></i>
                    You passed the guidance interview. The Registrar is now verifying your admission requirements.
                </div>
                @elseif($application->isInEnrollmentStage())
                <div class="alert alert-success mt-3 mb-0 small">
                    <i class="fas fa-user-check me-1"></i>
                    Your requirements are complete and your record is now in the enrollment stage.
                </div>
                @elseif($application->isInCashierStage())
                <div class="alert alert-dark mt-3 mb-0 small">
                    <i class="fas fa-cash-register me-1"></i>
                    Your enrollment has been processed and your record has been forwarded to Cashier for payment.
                </div>
                @elseif($application->isArchivedRecord())
                <div class="alert alert-secondary mt-3 mb-0 small">
                    <i class="fas fa-box-archive me-1"></i>
                    Your application was archived after the interview evaluation. Please contact the Guidance Office for details.
                </div>
                @elseif($application->workflow_stage === 'exam_failed')
                <div class="alert alert-danger mt-3 mb-0 small">
                    <i class="fas fa-circle-xmark me-1"></i>
                    You did not pass the entrance examination. Your record remains on file for reference.
                </div>
                @elseif($application->isRejected())
                <div class="alert alert-danger mt-3 mb-0 small">
                    <i class="fas fa-circle-xmark me-1"></i>
                    Unfortunately, your application was not approved. Please contact the Registrar's Office for more information.
                </div>
                @elseif(!$application->app_id)
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
                No application found with that ID or email address.
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