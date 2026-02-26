@extends('layouts.app')
@section('title', 'Enrollments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-journal-check me-1"></i> Enrollments</h6>
        <a href="{{ route('registrar.enrollments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> New Enrollment</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['pending','assessed','enrolled','dropped','completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="semester_id" class="form-select form-select-sm">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ request('semester_id') == $sem->id ? 'selected' : '' }}>
                            {{ $sem->academicYear->name ?? '' }} - {{ $sem->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>Student</th><th>Semester</th><th>Units</th><th>Amount</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enr)
                        <tr>
                            <td>{{ $enr->student->user->name ?? 'N/A' }}</td>
                            <td>{{ $enr->semester->academicYear->name ?? '' }} - {{ $enr->semester->name ?? '' }}</td>
                            <td>{{ $enr->total_units }}</td>
                            <td>&#8369;{{ number_format($enr->total_amount, 2) }}</td>
                            <td>
                                @php $c = ['pending'=>'warning','assessed'=>'info','enrolled'=>'success','dropped'=>'danger','completed'=>'secondary']; @endphp
                                <span class="badge bg-{{ $c[$enr->status] ?? 'secondary' }}">{{ ucfirst($enr->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('registrar.enrollments.show', $enr) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No enrollments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $enrollments->links() }}
    </div>
</div>
@endsection
