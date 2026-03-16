<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Payment – SCC Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/scc_logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        .pay-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(13, 31, 60, 0.18);
            overflow: hidden;
        }

        .pay-header {
            background: linear-gradient(135deg, #0d1f3c 0%, #1a5276 100%);
            padding: 1.5rem 2rem;
            color: #fff;
        }

        .pay-header .app-id-badge {
            display: inline-block;
            background: rgba(241, 196, 15, 0.18);
            border: 1px solid #f1c40f;
            color: #f1c40f;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 0.25rem 0.9rem;
            border-radius: 20px;
            margin-top: 0.35rem;
        }

        .summary-row td {
            padding: 0.4rem 0.6rem;
            font-size: 0.9rem;
        }

        .qr-wrapper {
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            display: inline-block;
        }

        .qr-wrapper svg {
            display: block;
        }

        .step-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .step-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.65rem;
            font-size: 0.88rem;
            color: #374151;
        }

        .step-num {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            background: #0d1f3c;
            color: #f1c40f;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .gcash-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #007aff;
            text-transform: uppercase;
        }

        .verified-banner {
            background: linear-gradient(135deg, #065f46, #059669);
            color: #fff;
            border-radius: 10px;
            padding: 1rem 1.25rem;
        }

        .submitted-banner {
            background: linear-gradient(135deg, #1e40af, #2563eb);
            color: #fff;
            border-radius: 10px;
            padding: 1rem 1.25rem;
        }
    </style>
</head>

<body>
    <div class="container py-3" style="max-width: 700px;">

        <!-- Topbar -->
        <div class="topbar">
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-right-to-bracket"></i> Login
            </a>
        </div>

        <!-- Header -->
        <div class="header py-3">
            <div class="clinic-logo">
                <img src="{{ asset('images/scc_logo.png') }}" alt="SCC Logo" />
            </div>
            <h1>Samuel Christian College</h1>
            <h2>Admission Payment</h2>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-circle-check"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-circle-info"></i> {{ session('info') }}
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger py-2 mb-3">
            @foreach($errors->all() as $error)
            <div><small><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</small></div>
            @endforeach
        </div>
        @endif

        <div class="pay-card">
            <!-- Header: student info -->
            <div class="pay-header">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">{{ $application->fullName() }}</h5>
                        <span class="app-id-badge"><i class="fas fa-hashtag me-1"></i>{{ $application->app_id }}</span>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <!-- Summary table -->
                <table class="table table-sm table-borderless summary-row mb-3">
                    <tr>
                        <td class="text-muted fw-semibold" style="width:40%">Program</td>
                        <td>{{ $application->program->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Admission Fee</td>
                        <td class="fw-bold text-success fs-5">₱50.00</td>
                        <!-- <td class="fw-bold text-success fs-5">₱{{ number_format($gcashFee, 2) }}</td> -->
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">Payment Status</td>
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
                </table>

                <hr>

                {{-- PAID state --}}
                @if($application->isPaymentPaid())
                <div class="verified-banner text-center">
                    <i class="fas fa-circle-check fa-2x mb-2"></i>
                    <p class="fw-bold mb-1">Payment Verified!</p>
                    <p class="small mb-0">Your admission payment has been confirmed. Your application is now being processed.</p>
                </div>

                {{-- SUBMITTED (awaiting verification) state --}}
                @elseif($application->hasPaymentSubmitted())
                <div class="submitted-banner text-center mb-3">
                    <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                    <p class="fw-bold mb-1">Payment Submitted – Under Review</p>
                    <p class="small mb-0">Your GCash receipt has been received. The registrar will verify your payment shortly.</p>
                </div>
                <p class="text-muted small text-center mb-0">
                    Submitted on: {{ $application->admissionPayment->submitted_at?->format('M d, Y h:i A') }}
                    &nbsp;|&nbsp; Ref #: <strong>{{ $application->admissionPayment->reference_number }}</strong>
                </p>

                {{-- PENDING state — show QR + submission form --}}
                @else

                {{-- QR Code (stacked above instructions to avoid overlap) --}}
                <div class="text-center mb-4">
                    <div class="gcash-label mb-2"><i class="fas fa-qrcode me-1"></i>Scan to Pay via GCash</div>

                    @if($gcashQrExists)
                    <div class="qr-wrapper mx-auto d-inline-block">
                        <img src="{{ asset($gcashQrImage) }}"
                            alt="GCash QR Code – {{ $gcashName }}"
                            style="width:260px; height:260px; object-fit:contain; display:block;">
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        Scan with the <strong>GCash</strong> app &nbsp;·&nbsp; <strong>{{ $gcashNumber}}</strong>
                    </p>
                    @else
                    {{-- Fallback when QR image not yet uploaded --}}
                    <div class="qr-wrapper mx-auto d-inline-flex flex-column align-items-center justify-content-center"
                        style="width:260px; height:260px; background:#f8fafc; border:2px dashed #94a3b8;">
                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                        <p class="text-muted small mb-0 px-3 text-center">
                            GCash QR not yet configured.<br>Send manually to <strong>{{ $gcashNumber }}</strong>.
                        </p>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-circle-info me-1"></i>
                        Admin: download QR from GCash app and save to <code>public/images/gcash_qr.png</code>
                    </p>
                    @endif
                </div>

                {{-- Payment Instructions (full-width below QR) --}}
                <!-- <div class="mb-3 p-3 rounded" style="background:#f8fafc; border:1px solid #e2e8f0;">
                    <p class="fw-semibold mb-2" style="color:#0d1f3c;">
                        <i class="fas fa-list-ol me-1"></i> Payment Instructions
                    </p>
                    <ul class="step-list mb-3">
                        <li><span class="step-num">1</span> Open the <strong>GCash</strong> app and tap <strong>Scan QR</strong>.</li>
                        <li><span class="step-num">2</span> Scan the QR code above. Recipient will be pre-filled as <strong>{{ $gcashName }}</strong>.</li>
                        <li><span class="step-num">3</span> Enter the amount: <strong>₱{{ number_format($gcashFee, 2) }}</strong>.</li>
                        <li><span class="step-num">4</span> In the <strong>Message/Note</strong> field, type your Application ID:
                            <br><span class="badge bg-dark mt-1" style="letter-spacing:1px; font-size:0.85rem;">{{ $application->app_id }}</span>
                        </li>
                        <li><span class="step-num">5</span> Complete the payment and <strong>save the receipt screenshot</strong>.</li>
                    </ul>
                    <div class="alert alert-warning py-2 small mb-0">
                        <i class="fas fa-triangle-exclamation me-1"></i>
                        After paying, fill in the form below and upload your GCash receipt to complete your application.
                    </div>
                </div> -->

                <hr class="my-4">

                <!-- Payment Submission Form -->
                <h6 class="fw-bold mb-3" style="color:#0d1f3c;">
                    <i class="fas fa-upload me-1"></i> Submit Your Payment Receipt
                </h6>
                <form method="POST" action="{{ route('admission.payment.store', $application->app_id) }}"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="reference_number" class="form-label fw-semibold">
                                GCash Reference Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror"
                                id="reference_number" name="reference_number"
                                value="{{ old('reference_number') }}"
                                placeholder="e.g. 1234567890"
                                required maxlength="50">
                            @error('reference_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Found at the bottom of your GCash receipt.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="receipt_image" class="form-label fw-semibold">
                                Receipt Screenshot <span class="text-danger">*</span>
                            </label>
                            <input type="file" class="form-control @error('receipt_image') is-invalid @enderror"
                                id="receipt_image" name="receipt_image"
                                accept="image/jpeg,image/png"
                                required>
                            @error('receipt_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">JPG or PNG, max 5 MB.</div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold py-2 mt-3 fs-6">
                        <i class="fas fa-paper-plane me-2"></i> Submit Payment Details
                    </button>
                </form>
                @endif

            </div>{{-- /p-4 --}}
        </div>{{-- /pay-card --}}

        <!-- Footer links -->
        <div class="text-center py-3">
            <a href="{{ route('admission.track') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.9);">
                <i class="fas fa-magnifying-glass me-1"></i> Track Application
            </a>
            <span class="mx-2" style="color:rgba(255,255,255,0.4);">|</span>
            <a href="{{ route('admission.jhs') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.9);">
                <i class="fas fa-pen-to-square me-1"></i> New Application
            </a>
            <span class="mx-2" style="color:rgba(255,255,255,0.4);">|</span>
            <a href="{{ route('login') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.9);">
                <i class="fas fa-right-to-bracket me-1"></i> Sign In
            </a>
        </div>
        <div class="text-center pb-3">
            <small style="color:rgba(255,255,255,0.55);">&copy; {{ date('Y') }} SCC Portal. All rights reserved.</small>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>