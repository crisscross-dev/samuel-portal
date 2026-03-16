@extends('layouts.app')
@section('title', 'Guidance Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Awaiting Scheduling</div>
                <div class="fs-4 fw-bold">{{ $stats['queued'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Interview Scheduled</div>
                <div class="fs-4 fw-bold text-warning">{{ $stats['scheduled'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Forms Submitted</div>
                <div class="fs-4 fw-bold text-success">{{ $stats['submitted'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Archived Interviews</div>
                <div class="fs-4 fw-bold text-secondary">{{ $stats['archived'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h6 class="mb-1">Interview Scheduler</h6>
            <p class="mb-0 text-muted small">Manage available slots and review scheduling history in one dedicated page.</p>
        </div>
        <a href="{{ route('guidance.scheduler.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-calendar2-week me-1"></i> Open Scheduler
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Recent Guidance Cases</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('guidance.applications.index') }}" class="btn btn-sm btn-outline-primary">Open Queue</a>
            <a href="{{ route('guidance.applications.results') }}" class="btn btn-sm btn-outline-secondary">View Remarks</a>
            <a href="{{ route('guidance.applications.logs') }}" class="btn btn-sm btn-outline-dark">Open Log</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Applicant</th>
                        <th>Program</th>
                        <th>Stage</th>
                        <th>Interview Date</th>
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
                        <td>{{ $application->workflowLabel() }}</td>
                        <td>{{ $application->interviewSlot?->interview_date?->format('M d, Y') ?: $application->interview_date?->format('M d, Y') ?: 'Not selected yet' }}</td>
                        <td class="text-end"><a href="{{ route('guidance.applications.show', $application) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No applications are in the guidance queue.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection