@extends('layouts.app')
@section('title', 'Application Details')

@section('content')
<div class="mb-3">
    <a href="{{ route('registrar.applications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Applications
    </a>
</div>

<div class="row g-4">
    {{-- Main Details --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-file-earmark-person me-1"></i> Application #{{ $application->id }}</h6>
                @php
                $badge = match($application->status) {
                'approved' => 'success',
                'rejected' => 'danger',
                default => 'warning',
                };
                @endphp
                <span class="badge bg-{{ $badge }} fs-6">{{ ucfirst($application->status) }}</span>
            </div>
            <div class="card-body">
                {{-- Personal Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-person me-1"></i> Personal Information</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" style="width:45%">First Name</td>
                                <td class="fw-semibold">{{ $application->first_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Last Name</td>
                                <td class="fw-semibold">{{ $application->last_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $application->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Contact</td>
                                <td>{{ $application->contact_number ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" style="width:45%">Date of Birth</td>
                                <td>{{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Gender</td>
                                <td>{{ $application->gender ? ucfirst($application->gender) : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Address</td>
                                <td>{{ $application->address ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                {{-- Academic Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-book me-1"></i> Academic Information</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" style="width:45%">Program</td>
                                <td class="fw-semibold">{{ $application->program->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Program Code</td>
                                <td>{{ $application->program->code ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Year Level</td>
                                <td>{{ $application->year_level }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                {{-- Guardian Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-shield-check me-1"></i> Guardian / Parent</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted" style="width:45%">Guardian Name</td>
                                <td>{{ $application->guardian_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Guardian Contact</td>
                                <td>{{ $application->guardian_contact ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Document --}}
                @if($application->document_path)
                <hr>
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-paperclip me-1"></i> Uploaded Document</h6>
                <a href="{{ asset('storage/' . $application->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download me-1"></i> View / Download Document
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar Actions --}}
    <div class="col-lg-4">
        {{-- Status & Timeline --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-clock-history me-1"></i> Timeline</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start gap-2 mb-3">
                    <i class="bi bi-circle-fill text-primary" style="font-size:0.5rem; margin-top:6px;"></i>
                    <div>
                        <div class="small fw-semibold">Application Submitted</div>
                        <div class="text-muted small">{{ $application->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @if($application->reviewed_at)
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-circle-fill text-{{ $application->isApproved() ? 'success' : 'danger' }}" style="font-size:0.5rem; margin-top:6px;"></i>
                    <div>
                        <div class="small fw-semibold">{{ $application->isApproved() ? 'Approved' : 'Rejected' }}</div>
                        <div class="text-muted small">{{ $application->reviewed_at->format('M d, Y h:i A') }}</div>
                        @if($application->reviewer)
                        <div class="text-muted small">By: {{ $application->reviewer->name }}</div>
                        @endif
                    </div>
                </div>
                @else
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-circle text-warning" style="font-size:0.5rem; margin-top:6px;"></i>
                    <div>
                        <div class="small fw-semibold text-warning">Awaiting Review</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Remarks --}}
        @if($application->remarks)
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-chat-left-text me-1"></i> Remarks</h6>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $application->remarks }}</p>
            </div>
        </div>
        @endif

        {{-- Exam Schedule --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-calendar-event me-1"></i> Entrance Exam</h6>
                <a href="{{ route('registrar.exam-schedules.index') }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:0.75rem;">
                    <i class="bi bi-gear me-1"></i>Manage Schedules
                </a>
            </div>
            <div class="card-body">
                {{-- Current schedule display --}}
                @if($application->examSchedule)
                @php
                $es = $application->examSchedule;
                $is9am = $es->time_slot === '9am';
                $booked = $es->applications_count ?? $es->applications()->count();
                $available = max(0, $es->max_capacity - $booked);
                @endphp
                <div class="text-center py-2 mb-3">
                    <div class="fw-bold" style="color:#0d1f3c; font-size:1rem;">
                        {{ $es->exam_date->format('l') }}
                    </div>
                    <div class="text-muted small mb-1">{{ $es->exam_date->format('F j, Y') }}</div>
                    @if($is9am)
                    <div class="fw-bold fs-4" style="color:#b45309">9:00 AM</div>
                    <span class="badge mt-1" style="background:#fef9c3; color:#92400e; border:1px solid #fde68a">Morning Session</span>
                    @else
                    <div class="fw-bold fs-4" style="color:#1d4ed8">1:00 PM</div>
                    <span class="badge mt-1" style="background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe">Afternoon Session</span>
                    @endif
                    <div class="mt-2 small text-muted">
                        <i class="bi bi-people me-1"></i>
                        <strong>{{ $booked }}</strong> / {{ $es->max_capacity }} booked
                        &nbsp;·&nbsp;
                        @if($available === 0)
                        <span class="text-danger fw-semibold">Full</span>
                        @elseif($available <= 5)
                            <span class="text-warning fw-semibold">{{ $available }} slots left</span>
                            @else
                            <span class="text-success">{{ $available }} slots available</span>
                            @endif
                    </div>
                </div>
                @elseif($application->exam_schedule)
                {{-- Legacy display for old string-only records --}}
                @php $is9am = $application->exam_schedule === 'saturday_9am'; @endphp
                <div class="text-center py-2 mb-3">
                    <div class="fw-bold" style="color:#0d1f3c; font-size:1rem;">Saturday</div>
                    @if($is9am)
                    <div class="fw-bold fs-4" style="color:#b45309">9:00 AM</div>
                    <span class="badge mt-1" style="background:#fef9c3; color:#92400e; border:1px solid #fde68a">Morning Session</span>
                    @else
                    <div class="fw-bold fs-4" style="color:#1d4ed8">1:00 PM</div>
                    <span class="badge mt-1" style="background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe">Afternoon Session</span>
                    @endif
                </div>
                @else
                <p class="text-muted small mb-3">No schedule selected yet.</p>
                @endif

                {{-- Registrar can assign/change the schedule --}}
                <form method="POST" action="{{ route('registrar.applications.assign-schedule', $application) }}">
                    @csrf @method('PATCH')
                    <label class="form-label small fw-semibold mb-1">
                        {{ $application->examSchedule ? 'Change Schedule' : 'Assign Schedule' }}
                    </label>
                    <select name="exam_schedule_id" class="form-select form-select-sm mb-2">
                        <option value="">— No schedule —</option>
                        @foreach($activeSchedules as $sched)
                        @php
                        $schedBooked = $sched->applications_count;
                        $schedAvailable = max(0, $sched->max_capacity - $schedBooked);
                        $schedLabel = $sched->exam_date->format('M j, Y') . ' – ' . ($sched->time_slot === '9am' ? '9:00 AM' : '1:00 PM');
                        $slotsInfo = $schedAvailable > 0 ? "({$schedAvailable} slots left)" : '(Full)';
                        @endphp
                        <option value="{{ $sched->id }}"
                            {{ $application->exam_schedule_id == $sched->id ? 'selected' : '' }}
                            {{ $schedAvailable === 0 && $application->exam_schedule_id != $sched->id ? 'disabled' : '' }}>
                            {{ $schedLabel }} {{ $slotsInfo }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-calendar-check me-1"></i> Save Schedule
                    </button>
                </form>
            </div>
        </div>

        {{-- Payment Status --}}
        @php $pay = $application->admissionPayment; @endphp
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-credit-card me-1"></i> GCash Payment</h6>
                @if($pay)
                @if($pay->isVerified())
                <span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                @else
                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending Verification</span>
                @endif
                @endif
            </div>
            <div class="card-body">
                @if(!$pay)
                <span class="badge bg-secondary">No payment submitted</span>
                @else
                {{-- GCash Reference Number --}}
                <div class="mb-3 rounded p-2" style="background:#f0f9ff;border:1px solid #bae6fd;">
                    <div class="small text-muted mb-1"><i class="bi bi-hash me-1"></i>GCash Reference Number</div>
                    <div class="fw-bold font-monospace" style="font-size:1.05rem;letter-spacing:1px;">{{ $pay->reference_number }}</div>
                    <div class="small text-muted mt-1"><i class="bi bi-clock me-1"></i>Submitted: {{ $pay->submitted_at ? $pay->submitted_at->format('M d, Y h:i A') : '—' }}</div>
                </div>

                {{-- Receipt Screenshot thumbnail (click to enlarge) --}}
                @if($pay->receipt_image)
                <div class="mb-3 text-center" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#receiptModal">
                    <img src="{{ asset('storage/' . $pay->receipt_image) }}"
                        alt="GCash Receipt"
                        class="img-thumbnail"
                        style="max-height:180px; width:100%; object-fit:contain; border:2px solid #bae6fd;">
                    <div class="small text-primary mt-1"><i class="bi bi-zoom-in me-1"></i>Click to view full receipt</div>
                </div>
                @else
                <div class="text-muted small mb-3"><i class="bi bi-image me-1"></i>No receipt image uploaded.</div>
                @endif

                {{-- Verified by info --}}
                @if($pay->isVerified())
                <div class="alert alert-success py-2 px-3 small mb-2">
                    <i class="bi bi-person-check-fill me-1"></i>
                    Verified by <strong>{{ $pay->verifier->name ?? 'Registrar' }}</strong>
                    on {{ $pay->verified_at->format('M d, Y h:i A') }}
                </div>
                @elseif($application->isPending())
                {{-- Verify Payment button --}}
                <button type="button" class="btn btn-success btn-sm w-100 fw-semibold"
                    data-bs-toggle="modal" data-bs-target="#verifyPaymentModal">
                    <i class="bi bi-patch-check me-1"></i> Verify Payment
                </button>
                @endif
                @endif
            </div>
        </div>

        {{-- Actions --}}
        @if($application->isPending())
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-gear me-1"></i> Actions</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <button type="button" class="btn btn-success w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="bi bi-check-circle me-1"></i> Approve Application
                    </button>
                </div>
                <button type="button" class="btn btn-outline-danger w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-1"></i> Reject Application
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Receipt Lightbox Modal --}}
@if(isset($pay) && $pay && $pay->receipt_image)
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-receipt me-1"></i> GCash Payment Receipt</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="{{ asset('storage/' . $pay->receipt_image) }}"
                    alt="GCash Receipt"
                    class="img-fluid rounded shadow-sm"
                    style="max-height:80vh;">
                <div class="mt-3 p-2 rounded" style="background:#f0f9ff;border:1px solid #bae6fd;">
                    <span class="text-muted small">Reference #:</span>
                    <strong class="font-monospace ms-1">{{ $pay->reference_number }}</strong>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ asset('storage/' . $pay->receipt_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Open Full Size
                </a>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Verify Payment Modal --}}
@if(isset($pay) && $pay && !$pay->isVerified() && $application->isPending())
<div class="modal fade" id="verifyPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.verify-payment', $application) }}">
                @csrf @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title"><i class="bi bi-patch-check me-1"></i> Verify GCash Payment</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Confirm that the GCash payment below is legitimate before marking it as verified:</p>
                    <div class="rounded p-3 mb-3" style="background:#f0f9ff;border:1px solid #bae6fd;">
                        <div class="small text-muted mb-1">Applicant</div>
                        <div class="fw-semibold mb-3">{{ $application->fullName() }}</div>
                        <div class="small text-muted mb-1">GCash Reference Number</div>
                        <div class="fw-bold font-monospace" style="font-size:1.2rem;letter-spacing:1px;">{{ $pay->reference_number }}</div>
                    </div>
                    @if($pay->receipt_image)
                    <a href="{{ asset('storage/' . $pay->receipt_image) }}" target="_blank"
                        class="btn btn-sm btn-outline-secondary w-100 mb-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Open Receipt in New Tab
                    </a>
                    @endif
                    <div class="alert alert-warning small py-2 px-3 mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Please cross-check this reference number with the GCash transaction record before confirming.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Confirm Payment Verified
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Approve Modal --}}
@if($application->isPending())
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.approve', $application) }}">
                @csrf @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title"><i class="bi bi-check-circle me-1"></i> Approve Application</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to approve <strong>{{ $application->fullName() }}</strong>'s application.</p>
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle me-1"></i> This will:
                        <ul class="mb-0 mt-1">
                            <li>Create a user account for the applicant</li>
                            <li>Generate a Student ID</li>
                            <li>Create a student profile with <strong>admitted</strong> status</li>
                            <li>Send a <strong>confirmation email</strong> with exam schedule &amp; login credentials</li>
                        </ul>
                    </div>
                    @if(!$pay || !$pay->isVerified())
                    <div class="alert alert-warning small py-2 px-3 mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>Payment not yet verified!</strong> Please verify the GCash payment before approving.
                    </div>
                    @else
                    <div class="alert alert-success small py-2 px-3 mb-3">
                        <i class="bi bi-patch-check-fill me-1"></i> GCash payment verified &mdash; ref # <strong class="font-monospace">{{ $pay->reference_number }}</strong>
                    </div>
                    @endif
                    @if($application->examSchedule)
                    <div class="alert alert-success py-1 px-2 small mb-3">
                        <i class="bi bi-calendar-check me-1"></i>
                        Exam slot: <strong>{{ $application->examSchedule->exam_date->format('l, M j, Y') }} &ndash; {{ $application->examSchedule->time_slot === '9am' ? '9:00 AM' : '1:00 PM' }}</strong>
                    </div>
                    @elseif($application->exam_schedule)
                    <div class="alert alert-success py-1 px-2 small mb-3">
                        <i class="bi bi-calendar-check me-1"></i>
                        Exam slot to confirm: <strong>{{ $application->exam_schedule === 'saturday_9am' ? 'Saturday – 9:00 AM' : 'Saturday – 1:00 PM' }}</strong>
                    </div>
                    @endif
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Remarks (optional)</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Approve &amp; Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.reject', $application) }}">
                @csrf @method('PATCH')
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title"><i class="bi bi-x-circle me-1"></i> Reject Application</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Reject <strong>{{ $application->fullName() }}</strong>'s application?</p>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Reason..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i> Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection