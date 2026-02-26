@extends('layouts.app')
@section('title', 'Student Details')

@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Student Profile</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">Student ID</th><td>{{ $student->student_id }}</td></tr>
                    <tr><th class="text-muted">Name</th><td>{{ $student->user->name }}</td></tr>
                    <tr><th class="text-muted">Email</th><td>{{ $student->user->email }}</td></tr>
                    <tr><th class="text-muted">Program</th><td>{{ $student->program->code ?? 'N/A' }} - {{ $student->program->name ?? '' }}</td></tr>
                    <tr><th class="text-muted">Year Level</th><td>{{ $student->year_level }}</td></tr>
                    <tr><th class="text-muted">Status</th><td><span class="badge bg-{{ $student->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($student->status) }}</span></td></tr>
                    <tr><th class="text-muted">Gender</th><td>{{ ucfirst($student->gender ?? 'N/A') }}</td></tr>
                    <tr><th class="text-muted">Contact</th><td>{{ $student->contact_number ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Guardian</th><td>{{ $student->guardian_name ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Admitted</th><td>{{ $student->admission_date?->format('M d, Y') ?? 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Enrollment History</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Semester</th><th>Units</th><th>Amount</th><th>Paid</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($student->enrollments as $enr)
                                <tr>
                                    <td>{{ $enr->semester->name ?? '' }}</td>
                                    <td>{{ $enr->total_units }}</td>
                                    <td>&#8369;{{ number_format($enr->total_amount, 2) }}</td>
                                    <td>&#8369;{{ number_format($enr->totalPaid(), 2) }}</td>
                                    <td><span class="badge bg-{{ $enr->status === 'enrolled' ? 'success' : 'warning' }}">{{ ucfirst($enr->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">No enrollments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
