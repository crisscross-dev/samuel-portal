@extends('layouts.app')
@section('title', 'Applications')

@section('content')
@php
$currentQueue = $stageGroupMeta[$selectedStageGroup];
@endphp

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('registrar.applications.index') }}" class="row g-2 align-items-end">
            @if($selectedStageGroup !== 'all')
            <input type="hidden" name="stage_group" value="{{ $selectedStageGroup }}">
            @endif
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Decision</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Decisions</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Workflow Stage</label>
                <select name="workflow_stage" class="form-select form-select-sm">
                    <option value="">All Stages</option>
                    <option value="submitted" {{ request('workflow_stage') === 'submitted' ? 'selected' : '' }}>Pending Review</option>
                    <option value="exam_approved" {{ request('workflow_stage') === 'exam_approved' ? 'selected' : '' }}>Approved for Exam</option>
                    <option value="guidance_review" {{ request('workflow_stage') === 'guidance_review' ? 'selected' : '' }}>Forwarded to Guidance</option>
                    <option value="interview_scheduled" {{ request('workflow_stage') === 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                    <option value="interview_form_submitted" {{ request('workflow_stage') === 'interview_form_submitted' ? 'selected' : '' }}>Interview Form Submitted</option>
                    <option value="registrar_requirements" {{ request('workflow_stage') === 'registrar_requirements' ? 'selected' : '' }}>Registrar Requirements</option>
                    <option value="enrollment" {{ request('workflow_stage') === 'enrollment' ? 'selected' : '' }}>Enrollment Stage</option>
                    <option value="cashier_payment" {{ request('workflow_stage') === 'cashier_payment' ? 'selected' : '' }}>Cashier Payment</option>
                    <option value="archived" {{ request('workflow_stage') === 'archived' ? 'selected' : '' }}>Archived</option>
                    <option value="exam_failed" {{ request('workflow_stage') === 'exam_failed' ? 'selected' : '' }}>Exam Failed</option>
                    <option value="rejected" {{ request('workflow_stage') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Program</label>
                <select name="program_id" class="form-select form-select-sm">
                    <option value="">All Programs</option>
                    @foreach($programs as $p)
                    <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-file-earmark-person me-1"></i> {{ $currentQueue['title'] }}</h6>
        <span class="badge bg-secondary">{{ $applications->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Applicant</th>
                        <th>Program</th>
                        <th>Exam Schedule</th>
                        <th>Payment</th>
                        <th>Exam Result</th>
                        <th>Interview Result</th>
                        <th>Workflow</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $app->fullName() }}</div>
                            <div class="text-muted small">{{ $app->email }}</div>
                            <div class="text-muted small">{{ $app->app_id ?: 'No App ID' }}</div>
                        </td>
                        <td>{{ $app->program->code ?? 'N/A' }}</td>
                        <td>{{ $app->examLabel() }}</td>
                        <td>
                            @if(!$app->admissionPayment)
                            <span class="badge bg-secondary">No payment</span>
                            @elseif($app->admissionPayment->payment_status === 'paid')
                            <span class="badge bg-success">Verified</span>
                            @else
                            <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $examBadge = match($app->exam_result) {
                            'passed' => 'bg-success',
                            'failed' => 'bg-danger',
                            default => 'bg-secondary',
                            };
                            @endphp
                            <span class="badge {{ $examBadge }}">{{ $app->examResultLabel() }}</span>
                        </td>
                        <td>{{ $app->interviewResultLabel() }}</td>
                        <td>
                            @php
                            $workflowBadge = match($app->workflow_stage) {
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
                            <span class="badge {{ $workflowBadge }}">{{ $app->workflowLabel() }}</span>
                            @unless($app->is_active)
                            <div><span class="badge bg-dark mt-1">Inactive</span></div>
                            @endunless
                            @if($app->is_archived)
                            <div><span class="badge bg-secondary mt-1">Archived</span></div>
                            @endif
                        </td>
                        <td class="text-end"><a href="{{ route('registrar.applications.show', $app) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No applications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($applications->hasPages())
    <div class="card-footer">{{ $applications->links() }}</div>
    @endif
</div>
@endsection