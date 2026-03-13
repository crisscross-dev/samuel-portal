@extends('layouts.app')
@section('title', 'Enrollment Dashboard')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-person-check me-1"></i> Students Ready for Enrollment</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary">{{ $enrollmentCandidates->count() }}</span>
            <a href="{{ route('registrar.enrollments.create') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg me-1"></i> Manual Enrollment</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Applicant</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Requirements Verified</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollmentCandidates as $candidate)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $candidate->fullName() }}</div>
                            <div class="text-muted small">{{ $candidate->email }}</div>
                        </td>
                        <td>{{ $candidate->program->code ?? 'N/A' }}</td>
                        <td>{{ $candidate->year_level }}</td>
                        <td>{{ $candidate->requirements_verified_at?->format('M d, Y h:i A') ?: 'Pending verification' }}</td>
                        <td class="text-end">
                            <a href="{{ route('registrar.enrollments.create', ['application_id' => $candidate->id]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-journal-plus me-1"></i> Start Enrollment
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <div class="fw-semibold mb-1">No students are ready for enrollment.</div>
                            <div class="small">Applicants will appear here after the Registrar completes requirements verification.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection