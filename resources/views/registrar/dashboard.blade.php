@extends('layouts.app')
@section('title', 'Registrar Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="text-muted small">Total Students</div>
                    <div class="fw-bold fs-5">{{ $stats['total_students'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-file-earmark-person"></i></div>
                <div>
                    <div class="text-muted small">Pending Apps</div>
                    <div class="fw-bold fs-5">{{ $stats['pending_applications'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="text-muted small">Enrolled</div>
                    <div class="fw-bold fs-5">{{ $stats['enrolled_this_sem'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-credit-card"></i></div>
                <div>
                    <div class="text-muted small">Pending Pay</div>
                    <div class="fw-bold fs-5">{{ $stats['pending_payments'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0">Admission Workflow Snapshot</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="border rounded p-2">
                    <div class="text-muted small">Pending Review</div>
                    <div class="fw-bold fs-5">{{ $stats['pending_review'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="border rounded p-2">
                    <div class="text-muted small">Approved for Exam</div>
                    <div class="fw-bold fs-5 text-info">{{ $stats['approved_for_exam'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="border rounded p-2">
                    <div class="text-muted small">Guidance Queue</div>
                    <div class="fw-bold fs-5 text-primary">{{ $stats['guidance_queue'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="border rounded p-2">
                    <div class="text-muted small">Requirements</div>
                    <div class="fw-bold fs-5 text-primary">{{ $stats['requirements_check'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="border rounded p-2">
                    <div class="text-muted small">Enrollment</div>
                    <div class="fw-bold fs-5 text-success">{{ $stats['enrollment_stage'] }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="border rounded p-2">
                    <div class="text-muted small">Cashier / Inactive</div>
                    <div class="fw-bold fs-5 text-dark">{{ $stats['cashier_inactive'] }}</div>
                    <div class="small text-danger">Inactive: {{ $stats['inactive'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($semester)
<div class="alert alert-info">
    <i class="bi bi-info-circle me-1"></i> Active Semester: <strong>{{ $semester->academicYear->name ?? '' }} - {{ $semester->name }}</strong>
</div>
@else
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-1"></i> No active semester. Ask the Admin to set one.
</div>
@endif

<div class="row g-4">
    {{-- Recent Applications --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-file-earmark-person me-1"></i> Pending Applications</h6>
                <a href="{{ route('registrar.applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApplications as $app)
                            <tr>
                                <td>
                                    <a href="{{ route('registrar.applications.show', $app) }}" class="text-decoration-none fw-semibold">
                                        {{ $app->fullName() }}
                                    </a>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $app->program->code ?? 'N/A' }}</span></td>
                                <td class="small text-muted">{{ $app->created_at->format('M d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No pending applications.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Enrollments --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-journal-check me-1"></i> Recent Enrollments</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEnrollments as $enr)
                            <tr>
                                <td>{{ $enr->student->user->name ?? 'N/A' }}</td>
                                <td>{{ $enr->semester->name ?? '' }}</td>
                                <td><span class="badge bg-{{ $enr->status === 'enrolled' ? 'success' : ($enr->status === 'assessed' ? 'info' : 'warning') }}">{{ ucfirst($enr->status) }}</span></td>
                                <td>&#8369;{{ number_format($enr->total_amount, 2) }}</td>
                                <td>{{ $enr->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No enrollments yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection