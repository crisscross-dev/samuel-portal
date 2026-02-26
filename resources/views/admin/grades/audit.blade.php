@extends('layouts.app')
@section('title', 'Grade Audit Log')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-clock-history me-1"></i> System-wide Grade Audit Log</h6>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="action" class="form-select form-select-sm" style="width: 160px;" onchange="this.form.submit()">
                    <option value="">All Actions</option>
                    @foreach(['created','updated','finalized','reopened','imported'] as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Student</th>
                        <th>Subject</th>
                        <th class="text-center">Old Grade</th>
                        <th class="text-center">New Grade</th>
                        <th>Notes</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-nowrap">
                                <small>{{ $log->performed_at->format('M d, Y h:i A') }}</small>
                            </td>
                            <td>{{ $log->user->name ?? 'System' }}</td>
                            <td>
                                <span class="badge bg-{{ $log->actionBadge() }}">{{ $log->actionLabel() }}</span>
                            </td>
                            <td>{{ $log->grade->student->user->name ?? 'N/A' }}</td>
                            <td>
                                <small>{{ $log->grade->enrollmentSubject->subject->code ?? '' }}</small>
                            </td>
                            <td class="text-center">
                                @if($log->old_grade !== null)
                                    <span class="text-muted">{{ number_format($log->old_grade, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($log->new_grade !== null)
                                    <span class="fw-semibold">{{ number_format($log->new_grade, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $log->notes ?? '—' }}</small></td>
                            <td><small class="text-muted">{{ $log->ip_address ?? '—' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-clock-history fs-1 d-block mb-2"></i> No audit records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
