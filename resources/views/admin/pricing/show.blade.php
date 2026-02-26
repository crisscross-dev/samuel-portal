@extends('layouts.app')
@section('title', 'Tuition Structure Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-tags me-1"></i> Tuition Structure Details</h5>
    <div class="d-flex gap-2">
        @can('admin')
            <a href="{{ route('admin.pricing.edit', $pricing) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
        @endcan
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0">Structure Overview</h6></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Label</th>
                        <td>{{ $pricing->label ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Department</th>
                        <td>
                            <span class="badge bg-primary">{{ $pricing->department->code ?? '?' }}</span>
                            {{ $pricing->department->name ?? '—' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Academic Year</th>
                        <td>{{ $pricing->academicYear->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Grade Level</th>
                        <td>{{ $pricing->gradeLevel->name ?? 'All' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Program</th>
                        <td>{{ $pricing->program->name ?? 'All' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Pricing Type</th>
                        <td>
                            <span class="badge bg-{{ $pricing->pricing_type === 'flat' ? 'info text-dark' : 'primary' }}">
                                {{ $pricing->pricing_type === 'flat' ? 'Flat Rate' : 'Per Unit' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status</th>
                        <td><span class="badge bg-{{ $pricing->is_active ? 'success' : 'danger' }}">{{ $pricing->is_active ? 'Active' : 'Inactive' }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Created</th>
                        <td>{{ $pricing->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-calculator me-1"></i> Rate Configuration</h6></div>
            <div class="card-body">
                @if($pricing->pricing_type === 'flat')
                    <div class="d-flex justify-content-between align-items-center bg-info bg-opacity-10 rounded p-3 mb-3">
                        <span class="fw-semibold">Flat Tuition Amount</span>
                        <span class="fs-5 fw-bold text-info">₱{{ number_format($pricing->flat_amount, 2) }}</span>
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center bg-primary bg-opacity-10 rounded p-3 mb-2">
                        <span class="fw-semibold">Lecture Unit Rate</span>
                        <span class="fs-5 fw-bold text-primary">₱{{ number_format($pricing->lecture_rate, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center bg-primary bg-opacity-10 rounded p-3 mb-3">
                        <span class="fw-semibold">Lab Unit Rate</span>
                        <span class="fs-5 fw-bold text-primary">₱{{ number_format($pricing->lab_rate, 2) }}</span>
                    </div>
                @endif

                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Miscellaneous Fee</span>
                    <span class="fw-semibold">₱{{ number_format($pricing->misc_fee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Registration Fee</span>
                    <span class="fw-semibold">₱{{ number_format($pricing->reg_fee, 2) }}</span>
                </div>

                <div class="alert alert-light small mb-0">
                    @if($pricing->pricing_type === 'flat')
                        <strong>Formula:</strong> ₱{{ number_format($pricing->flat_amount, 2) }} + ₱{{ number_format($pricing->misc_fee, 2) }} + ₱{{ number_format($pricing->reg_fee, 2) }}
                        = <strong>₱{{ number_format($pricing->flat_amount + $pricing->misc_fee + $pricing->reg_fee, 2) }}</strong> total per enrollment
                    @else
                        <strong>Formula:</strong> (Lec Units × ₱{{ number_format($pricing->lecture_rate, 2) }}) + (Lab Units × ₱{{ number_format($pricing->lab_rate, 2) }}) + ₱{{ number_format($pricing->misc_fee, 2) }} + ₱{{ number_format($pricing->reg_fee, 2) }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-journal-check me-1"></i> Linked Enrollments</h6>
                <span class="badge bg-primary">{{ $pricing->enrollments()->count() }}</span>
            </div>
            <div class="card-body">
                @if($pricing->enrollments()->count())
                    <p class="text-muted small mb-0">{{ $pricing->enrollments()->count() }} enrollment(s) use this pricing structure. Deletion is blocked while this link exists.</p>
                @else
                    <p class="text-muted small mb-0">No enrollments have been assessed using this structure yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
