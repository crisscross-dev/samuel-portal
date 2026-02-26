@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Program</h6>
                        <h4 class="mb-0">{{ $student->program->code ?? 'N/A' }}</h4>
                    </div>
                    <i class="bi bi-mortarboard fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Year Level</h6>
                        <h4 class="mb-0">{{ $student->year_level ?? '-' }}</h4>
                    </div>
                    <i class="bi bi-calendar-check fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Status</h6>
                        <h4 class="mb-0">{{ ucfirst($student->status) }}</h4>
                    </div>
                    <i class="bi bi-person-badge fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Student ID</h6>
                        <h4 class="mb-0">{{ $student->student_id }}</h4>
                    </div>
                    <i class="bi bi-card-heading fs-2 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if($enrollment)
    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0"><i class="bi bi-journal-bookmark-fill me-1"></i> Current Enrollment — {{ $semester->academicYear->name ?? '' }}, {{ $semester->name ?? '' }}</h6></div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-3"><strong>Units:</strong> {{ $stats['units'] ?? 0 }}</div>
                <div class="col-md-3"><strong>Total Amount:</strong> ₱{{ number_format($stats['total_amount'] ?? 0, 2) }}</div>
                <div class="col-md-3"><strong>Paid:</strong> ₱{{ number_format($stats['total_paid'] ?? 0, 2) }}</div>
                <div class="col-md-3"><strong>Balance:</strong> ₱{{ number_format($stats['balance'] ?? 0, 2) }}</div>
            </div>
            <span class="badge bg-{{ $enrollment->status === 'enrolled' ? 'success' : ($enrollment->status === 'finalized' ? 'primary' : 'warning') }} fs-6">
                {{ ucfirst($enrollment->status) }}
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h6 class="mb-0"><i class="bi bi-list-columns-reverse me-1"></i> Enrolled Subjects</h6></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Subject</th>
                            <th>Section</th>
                            <th>Schedule</th>
                            <th>Room</th>
                            <th>Instructor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollment->enrollmentSubjects as $es)
                            <tr>
                                <td>{{ $es->subject->code }}</td>
                                <td>{{ $es->subject->name }}</td>
                                <td>{{ $es->section->name ?? '-' }}</td>
                                <td>{{ $es->section->schedule ?? '-' }}</td>
                                <td>{{ $es->section->room ?? '-' }}</td>
                                <td>{{ $es->section->faculty->user->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info"><i class="bi bi-info-circle me-1"></i> You are not currently enrolled for this semester.</div>
@endif
@endsection
