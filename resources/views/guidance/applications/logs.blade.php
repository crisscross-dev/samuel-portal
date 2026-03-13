@extends('layouts.app')
@section('title', 'Interview Log')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('guidance.applications.logs') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Interview Result</label>
                <select name="interview_result" class="form-select form-select-sm">
                    <option value="">All Results</option>
                    <option value="passed" {{ request('interview_result') === 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ request('interview_result') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Workflow Stage</label>
                <select name="workflow_stage" class="form-select form-select-sm">
                    <option value="">All Stages</option>
                    <option value="registrar_requirements" {{ request('workflow_stage') === 'registrar_requirements' ? 'selected' : '' }}>Returned to Registrar</option>
                    <option value="enrollment" {{ request('workflow_stage') === 'enrollment' ? 'selected' : '' }}>Enrollment Stage</option>
                    <option value="cashier_payment" {{ request('workflow_stage') === 'cashier_payment' ? 'selected' : '' }}>Cashier Payment</option>
                    <option value="archived" {{ request('workflow_stage') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="col-md-5">
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
        <h6 class="mb-0">Completed Interview Log</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Applicant</th>
                        <th>Program</th>
                        <th>Interview Result</th>
                        <th>Current Stage</th>
                        <th>Evaluated On</th>
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
                        <td>{{ $application->program->code ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $application->interview_result === 'passed' ? 'success' : 'danger' }}">{{ $application->interviewResultLabel() }}</span>
                        </td>
                        <td>{{ $application->workflowLabel() }}</td>
                        <td>{{ $application->interview_evaluated_at?->format('M d, Y h:i A') ?: 'N/A' }}</td>
                        <td>{{ $application->interview_remarks ?: $application->guidance_remarks ?: 'No remarks recorded' }}</td>
                        <td class="text-end"><a href="{{ route('guidance.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No interview logs found.</td>
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