@extends('layouts.app')
@section('title', 'Interview Remarks')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('guidance.applications.results') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Workflow Stage</label>
                <select name="workflow_stage" class="form-select form-select-sm">
                    <option value="">All Stages</option>
                    <option value="interview_form_submitted" {{ request('workflow_stage') === 'interview_form_submitted' ? 'selected' : '' }}>Interview Form Submitted</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Name or email">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Interview Remarks Pending Evaluation</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Applicant</th>
                        <th>Program</th>
                        <th>Interview Date</th>
                        <th>Form Status</th>
                        <th>Current Stage</th>
                        <th>Remarks</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $application->fullName() }}</div>
                            <div class="text-muted small">{{ $application->email }}</div>
                        </td>
                        <td>
                            {{ $application->program->code ?? 'N/A' }}
                            @php
                            $isShs = str_starts_with(strtoupper((string) optional($application->program)->code), 'SHS-');
                            @endphp
                            <span class="badge bg-{{ $isShs ? 'info' : 'primary' }} ms-1">{{ $isShs ? 'SHS' : 'JHS' }}</span>
                        </td>
                        <td>{{ $application->interviewSlot?->interview_date?->format('M d, Y') ?: $application->interview_date?->format('M d, Y') ?: 'Not selected yet' }}</td>
                        <td>{{ $application->interview_form_submitted_at ? 'Submitted' : 'Awaiting applicant' }}</td>
                        <td>{{ $application->workflowLabel() }}</td>
                        <td>{{ $application->interview_remarks ?: $application->guidance_remarks ?: 'No remarks yet' }}</td>
                        <td class="text-end"><a href="{{ route('guidance.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No interview remarks are waiting for evaluation.</td>
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