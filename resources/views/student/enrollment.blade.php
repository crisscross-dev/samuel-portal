@extends('layouts.app')
@section('title', 'Enrollment History')

@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0"><i class="bi bi-journal-text me-1"></i> Enrollment History</h5></div>
    <div class="card-body">
        @forelse($enrollments as $enrollment)
            <div class="card mb-3 border-start border-4 border-{{ $enrollment->status === 'enrolled' ? 'success' : ($enrollment->status === 'finalized' ? 'primary' : 'warning') }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $enrollment->semester->academicYear->name ?? '' }} — {{ $enrollment->semester->name ?? '' }}</strong>
                        <span class="badge bg-{{ $enrollment->status === 'enrolled' ? 'success' : ($enrollment->status === 'finalized' ? 'primary' : 'warning') }} ms-2">{{ ucfirst($enrollment->status) }}</span>
                    </div>
                    <div>
                        <span class="me-3">Units: <strong>{{ $enrollment->total_units }}</strong></span>
                        <span class="me-3">Amount: <strong>₱{{ number_format($enrollment->total_amount, 2) }}</strong></span>
                        <a href="{{ route('student.payments', $enrollment) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-receipt me-1"></i> Payments</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Section</th>
                                <th>Schedule</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollment->enrollmentSubjects as $es)
                                <tr>
                                    <td>{{ $es->subject->code ?? '' }}</td>
                                    <td>{{ $es->subject->name ?? '' }}</td>
                                    <td>{{ $es->section->name ?? '-' }}</td>
                                    <td>{{ $es->section->schedule ?? '-' }}</td>
                                    <td>{{ $es->section->room ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4"><i class="bi bi-archive fs-1 d-block mb-2"></i> No enrollment records found.</div>
        @endforelse
    </div>
</div>
@endsection
