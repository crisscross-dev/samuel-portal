@extends('layouts.app')
@section('title', 'Scheduling Log')

@section('content')
<div class="card mb-4">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h6 class="mb-1">Interview Scheduling Log</h6>
            <p class="mb-0 text-muted small">History of created, used, and deactivated interview slots.</p>
        </div>
        <a href="{{ route('guidance.scheduler.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Back to Scheduler
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Schedule</th>
                        <th>Applicant</th>
                        <th>Created By</th>
                        <th>Status Log</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slotLogs as $slot)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $slot->form_type === 'shs' ? 'info' : 'primary' }}">{{ strtoupper($slot->form_type) }}</span>
                        </td>
                        <td>
                            {{ $slot->interview_date->format('M d, Y') }}<br>
                            <span class="text-muted small">{{ \Illuminate\Support\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Illuminate\Support\Carbon::parse($slot->end_time)->format('h:i A') }}</span>
                        </td>
                        <td>
                            @if($slot->application)
                            <div class="fw-semibold">{{ $slot->application->fullName() }}</div>
                            <div class="small text-muted">{{ $slot->application->email }}</div>
                            @else
                            <span class="text-muted small">No assignment</span>
                            @endif
                        </td>
                        <td>{{ $slot->creator?->name ?? 'System' }}</td>
                        <td>
                            @if($slot->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @if($slot->deactivation_reason)
                            <div class="small text-muted mt-1">Reason: {{ ucfirst($slot->deactivation_reason) }}</div>
                            @endif
                            @if($slot->deactivated_at)
                            <div class="small text-muted">On {{ $slot->deactivated_at->format('M d, Y h:i A') }}</div>
                            @endif
                            @endif
                        </td>
                        <td>{{ $slot->updated_at?->format('M d, Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No schedule logs yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($slotLogs->hasPages())
    <div class="card-footer">{{ $slotLogs->links() }}</div>
    @endif
</div>
@endsection