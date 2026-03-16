@extends('layouts.app')
@section('title', 'Guidance Application')

@section('content')
<div class="mb-3">
    <a href="{{ route('guidance.applications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Queue
    </a>
</div>

<div class="row g-4">
    @php
    $form = $application->interview_form_data ?? [];
    $hasSubmittedForm = !empty($form);
    $formType = $form['form_type'] ?? (str_starts_with(strtoupper((string) optional($application->program)->code), 'SHS-') ? 'shs' : 'jhs');
    @endphp
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ $application->fullName() }}</h6>
                <span class="badge bg-{{ $application->workflowBadgeClass() }}">{{ $application->workflowLabel() }}</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width:40%">Email</td>
                                <td>{{ $application->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Program</td>
                                <td>
                                    {{ $application->program->name ?? 'N/A' }}
                                    @php
                                    $isShsTrack = str_starts_with(strtoupper((string) optional($application->program)->code), 'SHS-');
                                    @endphp
                                    <span class="badge bg-{{ $isShsTrack ? 'info' : 'primary' }} ms-1">{{ $isShsTrack ? 'SHS' : 'JHS' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Year Level</td>
                                <td>{{ $application->year_level }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Exam Result</td>
                                <td>{{ $application->examResultLabel() }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Interview Result</td>
                                <td>{{ $application->interviewResultLabel() }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width:40%">Forwarded On</td>
                                <td>{{ $application->forwarded_to_guidance_at?->format('M d, Y h:i A') ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Interview Date</td>
                                <td>{{ $application->interviewSlot?->interview_date?->format('F d, Y') ?: $application->interview_date?->format('F d, Y') ?: 'Not selected yet' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Interview Slot</td>
                                <td>
                                    @if($application->interviewSlot)
                                    {{ \Illuminate\Support\Carbon::parse($application->interviewSlot->start_time)->format('h:i A') }} - {{ \Illuminate\Support\Carbon::parse($application->interviewSlot->end_time)->format('h:i A') }}
                                    @else
                                    Not selected yet
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Form Link Sent</td>
                                <td>{{ $application->interview_form_sent_at?->format('M d, Y h:i A') ?: 'Not yet sent' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Form Submitted</td>
                                <td>{{ $application->interview_form_submitted_at?->format('M d, Y h:i A') ?: 'Awaiting applicant' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Archived</td>
                                <td>{{ $application->isArchivedRecord() ? 'Yes' : 'No' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Applicant Information Available to Guidance</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><strong>Address:</strong><br>{{ $application->address ?: 'N/A' }}</div>
                    <div class="col-md-6"><strong>Contact:</strong><br>{{ $application->contact_number ?: 'N/A' }}</div>
                    <div class="col-md-6"><strong>Guardian:</strong><br>{{ $application->guardian_name ?: 'N/A' }}</div>
                    <div class="col-md-6"><strong>Guardian Contact:</strong><br>{{ $application->guardian_contact ?: 'N/A' }}</div>
                    <div class="col-md-6"><strong>LRN:</strong><br>{{ $application->lrn ?: 'N/A' }}</div>
                    <div class="col-md-6"><strong>Elementary School:</strong><br>{{ $application->elementary_school ?: 'N/A' }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Submitted {{ strtoupper($formType) }} Form Details</h6>
                <span class="badge bg-{{ $hasSubmittedForm ? 'success' : 'secondary' }}">{{ $hasSubmittedForm ? 'Submitted' : 'Not submitted' }}</span>
            </div>
            <div class="card-body">
                @if(!$hasSubmittedForm)
                <p class="text-muted mb-0">The applicant has not submitted the interview form yet.</p>
                @else
                <div class="row g-3 small">
                    <div class="col-md-4"><strong>Date of Enrollment:</strong><br>{{ $form['date_of_enrollment'] ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Classification:</strong><br>{{ $form['student_classification'] ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Type of Subsidy:</strong><br>{{ $form['type_of_subsidy'] ?? 'N/A' }}</div>
                    @if($formType === 'jhs')
                    <div class="col-md-4"><strong>Previous School:</strong><br>{{ $form['previous_school_classification'] ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>ESC Grantee:</strong><br>{{ $form['esc_grantee'] ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Preferred Interview Time:</strong><br>{{ $form['preferred_interview_time'] ?? 'N/A' }}</div>
                    @else
                    <div class="col-md-4"><strong>Elective Course:</strong><br>{{ $form['elective_course'] ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Strand:</strong><br>{{ $form['strand'] ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Last Year &amp; Section:</strong><br>{{ $form['last_year_section'] ?? 'N/A' }}</div>
                    @endif
                    <div class="col-md-6"><strong>Father:</strong><br>{{ $form['father_name'] ?? 'N/A' }} ({{ $form['father_contact'] ?? 'N/A' }})</div>
                    <div class="col-md-6"><strong>Mother:</strong><br>{{ $form['mother_name'] ?? 'N/A' }} ({{ $form['mother_contact'] ?? 'N/A' }})</div>
                    <div class="col-md-6"><strong>Place of Birth:</strong><br>{{ $form['place_of_birth'] ?? 'N/A' }}</div>
                    <div class="col-md-6"><strong>Extension Name:</strong><br>{{ $form['extension_name'] ?? 'N/A' }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Send Interview Form Link</h6>
            </div>
            <div class="card-body">
                @if($application->canScheduleInterview())
                <form method="POST" action="{{ route('guidance.applications.schedule-interview', $application) }}" class="d-grid gap-3">
                    @csrf
                    @method('PATCH')
                    <div class="alert alert-info mb-0 small">
                        Applicants stay in the queue after receiving the form link. They will choose an available schedule slot when submitting their form.
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Guidance Remarks</label>
                        <textarea name="guidance_remarks" class="form-control" rows="4" placeholder="Optional notes for the applicant or internal tracking">{{ old('guidance_remarks', $application->guidance_remarks) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Form Link</button>
                </form>
                @else
                <div class="alert alert-secondary mb-0 small">
                    Form link sending is locked for this workflow stage.
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Applicant Form Link</h6>
            </div>
            <div class="card-body">
                @if($application->interviewFormUrl())
                <div class="small text-muted mb-2">This link is emailed automatically after you send the form link.</div>
                <input type="text" class="form-control form-control-sm" readonly value="{{ $application->interviewFormUrl() }}">
                @else
                <div class="text-muted small">Send the form link first to generate the applicant link.</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Interview Evaluation</h6>
            </div>
            <div class="card-body">
                @if($application->isReadyForInterviewEvaluation())
                <form method="POST" action="{{ route('guidance.applications.evaluate-interview', $application) }}" class="d-grid gap-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="form-label fw-semibold">Interview Decision</label>
                        <select name="interview_result" class="form-select" required>
                            <option value="">Select result</option>
                            <option value="passed" {{ old('interview_result', $application->interview_result) === 'passed' ? 'selected' : '' }}>Passed</option>
                            <option value="considered" {{ old('interview_result', $application->interview_result) === 'considered' ? 'selected' : '' }}>Considered</option>
                            <option value="failed" {{ old('interview_result', $application->interview_result) === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Interview Remarks</label>
                        <textarea name="interview_remarks" class="form-control" rows="4" placeholder="Record the interview findings or the archive reason">{{ old('interview_remarks', $application->interview_remarks) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Interview Decision</button>
                </form>
                @else
                <div class="small text-muted">
                    @if($application->isArchivedRecord())
                    This record is archived and can no longer be updated by Guidance.
                    @elseif($application->interview_result)
                    Interview evaluation has already been completed.
                    @else
                    Schedule the interview first before recording an interview decision.
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection