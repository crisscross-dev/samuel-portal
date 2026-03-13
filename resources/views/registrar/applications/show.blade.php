@extends('layouts.app')
@section('title', 'Application Details')

@section('content')
<div class="mb-3">
    <a href="{{ route('registrar.applications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Applications
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-file-earmark-person me-1"></i> {{ $application->fullName() }}</h6>
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
                <span class="badge {{ $workflowBadge }}">{{ $application->workflowLabel() }}</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="text-primary">Applicant Details</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width:40%">Application ID</td>
                                <td>{{ $application->app_id ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $application->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Contact</td>
                                <td>{{ $application->contact_number ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date of Birth</td>
                                <td>{{ $application->date_of_birth?->format('M d, Y') ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Address</td>
                                <td>{{ $application->address ?: 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Admission Progress</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width:40%">Program</td>
                                <td>{{ $application->program->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Year Level</td>
                                <td>{{ $application->year_level }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Exam Schedule</td>
                                <td>{{ $application->examLabel() }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Payment</td>
                                <td>{{ $application->payment_status ? ucfirst($application->payment_status) : 'Pending' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Exam Result</td>
                                <td>{{ $application->examResultLabel() }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Record State</td>
                                <td>{{ $application->is_active ? 'Active' : 'Inactive' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Interview Result</td>
                                <td>{{ $application->interviewResultLabel() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-clock-history me-1"></i> Timeline</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="fw-semibold">Application Submitted</div>
                    <div class="text-muted small">{{ $application->created_at->format('M d, Y h:i A') }}</div>
                </div>
                @if($application->reviewed_at)
                <div class="mb-3">
                    <div class="fw-semibold">Registrar Review Completed</div>
                    <div class="text-muted small">{{ $application->reviewed_at->format('M d, Y h:i A') }}{{ $application->reviewer ? ' by ' . $application->reviewer->name : '' }}</div>
                    @if($application->remarks)
                    <div class="small mt-1">{{ $application->remarks }}</div>
                    @endif
                </div>
                @endif
                @if($application->exam_result_recorded_at)
                <div class="mb-3">
                    <div class="fw-semibold">Entrance Exam Result: {{ $application->examResultLabel() }}</div>
                    <div class="text-muted small">{{ $application->exam_result_recorded_at->format('M d, Y h:i A') }}{{ $application->examReviewer ? ' by ' . $application->examReviewer->name : '' }}</div>
                    @if($application->exam_remarks)
                    <div class="small mt-1">{{ $application->exam_remarks }}</div>
                    @endif
                </div>
                @endif
                @if($application->forwarded_to_guidance_at)
                <div class="mb-3">
                    <div class="fw-semibold">Forwarded to Guidance</div>
                    <div class="text-muted small">{{ $application->forwarded_to_guidance_at->format('M d, Y h:i A') }}</div>
                </div>
                @endif
                @if($application->interview_date)
                <div class="mb-3">
                    <div class="fw-semibold">Interview Scheduled</div>
                    <div class="text-muted small">{{ $application->interview_date->format('F d, Y') }}{{ $application->guidanceUser ? ' by ' . $application->guidanceUser->name : '' }}</div>
                </div>
                @endif
                @if($application->interview_form_submitted_at)
                <div>
                    <div class="fw-semibold">Interview Form Submitted</div>
                    <div class="text-muted small">{{ $application->interview_form_submitted_at->format('M d, Y h:i A') }}</div>
                </div>
                @endif
                @if($application->interview_evaluated_at)
                <div class="mb-3">
                    <div class="fw-semibold">Interview Result: {{ $application->interviewResultLabel() }}</div>
                    <div class="text-muted small">{{ $application->interview_evaluated_at->format('M d, Y h:i A') }}{{ $application->interviewEvaluator ? ' by ' . $application->interviewEvaluator->name : '' }}</div>
                    @if($application->interview_remarks)
                    <div class="small mt-1">{{ $application->interview_remarks }}</div>
                    @endif
                </div>
                @endif
                @if($application->requirements_verified_at)
                <div class="mb-3">
                    <div class="fw-semibold">Requirements Verified</div>
                    <div class="text-muted small">{{ $application->requirements_verified_at->format('M d, Y h:i A') }}{{ $application->requirementsVerifier ? ' by ' . $application->requirementsVerifier->name : '' }}</div>
                    @if($application->requirements_remarks)
                    <div class="small mt-1">{{ $application->requirements_remarks }}</div>
                    @endif
                </div>
                @endif
                @if($application->enrollment_processed_at)
                <div class="mb-3">
                    <div class="fw-semibold">Enrollment Processed</div>
                    <div class="text-muted small">{{ $application->enrollment_processed_at->format('M d, Y h:i A') }}{{ $application->enrollmentProcessor ? ' by ' . $application->enrollmentProcessor->name : '' }}</div>
                </div>
                @endif
                @if($application->archived_at)
                <div>
                    <div class="fw-semibold">Record Archived</div>
                    <div class="text-muted small">{{ $application->archived_at->format('M d, Y h:i A') }}</div>
                    @if($application->archive_reason)
                    <div class="small mt-1">{{ $application->archive_reason }}</div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-calendar-event me-1"></i> Entrance Exam Schedule</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">Assign an exam slot before clearing the applicant for the examination.</p>
                <form method="POST" action="{{ route('registrar.applications.assign-schedule', $application) }}">
                    @csrf
                    @method('PATCH')
                    <select name="exam_schedule_id" class="form-select form-select-sm mb-2">
                        <option value="">— No schedule —</option>
                        @foreach($activeSchedules as $sched)
                        @php
                        $schedBooked = $sched->applications_count;
                        $schedAvailable = max(0, $sched->max_capacity - $schedBooked);
                        @endphp
                        <option value="{{ $sched->id }}" {{ $application->exam_schedule_id == $sched->id ? 'selected' : '' }} {{ $schedAvailable === 0 && $application->exam_schedule_id != $sched->id ? 'disabled' : '' }}>
                            {{ $sched->exam_date->format('M d, Y') }} - {{ $sched->time_slot === '9am' ? '9:00 AM' : '1:00 PM' }} ({{ $schedAvailable }} slots left)
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Save Schedule</button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-credit-card me-1"></i> Payment Verification</h6>
            </div>
            <div class="card-body">
                @if(!$application->admissionPayment)
                <div class="text-muted small">No payment uploaded yet.</div>
                @else
                @php
                $receiptImageUrl = $application->admissionPayment->receipt_image
                ? asset('storage/' . ltrim($application->admissionPayment->receipt_image, '/'))
                : null;
                @endphp
                <div class="small mb-3">
                    <div><strong>Reference:</strong> {{ $application->admissionPayment->reference_number }}</div>
                    <div class="text-muted">Submitted {{ $application->admissionPayment->submitted_at?->format('M d, Y h:i A') }}</div>
                </div>
                @if($receiptImageUrl)
                <a href="{{ $receiptImageUrl }}" target="_blank" rel="noopener" class="d-block mb-3 text-decoration-none">
                    <img src="{{ $receiptImageUrl }}" alt="GCash receipt screenshot" class="img-fluid rounded border w-100">
                    <div class="small text-muted mt-2">Click the receipt to view the full image.</div>
                </a>
                @endif

                @if($application->admissionPayment->payment_status === 'paid')
                <div class="alert alert-success py-2 mb-0 small">Payment already verified.</div>
                @else
                <form method="POST" action="{{ route('registrar.applications.verify-payment', $application) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success w-100">Verify Payment</button>
                </form>
                @endif
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-check2-square me-1"></i> Registrar Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">
                @if($application->isPending())
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">Approve for Entrance Exam</button>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject Application</button>
                @elseif($application->isExamApproved())
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#passExamModal">Mark Exam as Passed</button>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#failExamModal">Mark Exam as Failed</button>
                @elseif($application->isInRegistrarRequirements())
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requirementsModal">Verify Requirements</button>
                @elseif($application->isInEnrollmentStage())
                <a href="{{ route('registrar.enrollments.create', ['application_id' => $application->id]) }}" class="btn btn-success">Open in Enrollment Dashboard</a>
                @else
                <div class="text-muted small">No registrar action is available for the current workflow stage.</div>
                @endif
            </div>
        </div>

        @if($application->guidance_remarks)
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-chat-left-text me-1"></i> Guidance Notes</h6>
            </div>
            <div class="card-body">
                <p class="mb-0 small">{{ $application->guidance_remarks }}</p>
            </div>
        </div>
        @endif

        @if($application->isInRegistrarRequirements() || $application->isInEnrollmentStage() || $application->isInCashierStage())
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-folder-check me-1"></i> Requirements Checklist</h6>
            </div>
            <div class="card-body small">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Pre-Enrolment Form</span>
                    <span class="fw-semibold">{{ $application->pre_enrolment_form_submitted ? 'Complete' : 'Pending' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Student Health Form</span>
                    <span class="fw-semibold">{{ $application->student_health_form_submitted ? 'Complete' : 'Pending' }}</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span>Original Report Card (SF9)</span>
                    <span class="fw-semibold">{{ $application->report_card_submitted ? 'Complete' : 'Pending' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>1x1 ID Picture</span>
                    <span class="fw-semibold">{{ $application->id_picture_submitted ? 'Complete' : 'Pending' }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@if($application->isPending())
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.approve', $application) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title">Approve for Entrance Exam</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">This step clears the applicant to take the entrance exam. It does not create a student account.</p>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Optional registrar remarks"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Approval</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.reject', $application) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title">Reject Application</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea name="remarks" class="form-control" rows="3" placeholder="Reason for rejection" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($application->isExamApproved())
<div class="modal fade" id="passExamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.exam-result', $application) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="exam_result" value="passed">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Record Passed Exam Result</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">This will forward the applicant to the Guidance Office.</p>
                    <textarea name="exam_remarks" class="form-control" rows="3" placeholder="Optional exam remarks"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Pass</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="failExamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.exam-result', $application) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="exam_result" value="failed">
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title">Record Failed Exam Result</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">This will keep the application on file and mark the record as inactive.</p>
                    <textarea name="exam_remarks" class="form-control" rows="3" placeholder="Reason or remarks" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Failure</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if($application->isInRegistrarRequirements())
<div class="modal fade" id="requirementsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.applications.verify-requirements', $application) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title">Verify Admission Requirements</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="pre_enrolment_form_submitted" id="preEnrolmentForm" value="1" {{ old('pre_enrolment_form_submitted', $application->pre_enrolment_form_submitted) ? 'checked' : '' }}>
                        <label class="form-check-label" for="preEnrolmentForm">Accomplished Pre-Enrolment Form</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="student_health_form_submitted" id="studentHealthForm" value="1" {{ old('student_health_form_submitted', $application->student_health_form_submitted) ? 'checked' : '' }}>
                        <label class="form-check-label" for="studentHealthForm">Accomplished Student Health Form</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="report_card_submitted" id="reportCard" value="1" {{ old('report_card_submitted', $application->report_card_submitted) ? 'checked' : '' }}>
                        <label class="form-check-label" for="reportCard">Original Report Card (SF9)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="id_picture_submitted" id="idPicture" value="1" {{ old('id_picture_submitted', $application->id_picture_submitted) ? 'checked' : '' }}>
                        <label class="form-check-label" for="idPicture">1x1 ID Picture</label>
                    </div>
                    <textarea name="requirements_remarks" class="form-control" rows="3" placeholder="Optional registrar notes">{{ old('requirements_remarks', $application->requirements_remarks) }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Checklist</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection