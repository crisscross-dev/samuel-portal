@extends('layouts.app')
@section('title', 'Grade Audit Log')

@section('content')
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-clock-history me-1"></i>
            Audit Log: {{ $sectionSubject->subject->code }} — {{ $sectionSubject->subject->name }}
            | Section: {{ $sectionSubject->section->name }}
        </h6>
        <a href="{{ route('faculty.grades.edit', $sectionSubject) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Grades
        </a>
    </div>
    <div class="card-body py-2">
        <small class="text-muted">
            <i class="bi bi-building me-1"></i> {{ $sectionSubject->section->gradeLevel->department->name ?? '' }}
            &nbsp;|&nbsp; <i class="bi bi-mortarboard me-1"></i> {{ $sectionSubject->section->gradeLevel->name ?? '' }}
        </small>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Student</th>
                        <th class="text-center">Old Grade</th>
                        <th class="text-center">New Grade</th>
                        <th>Notes</th>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
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
