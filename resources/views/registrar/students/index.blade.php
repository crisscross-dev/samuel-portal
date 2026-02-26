@extends('layouts.app')
@section('title', 'Student Records')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-person-badge me-1"></i> Student Records</h6>
        <a href="{{ route('registrar.students.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Student</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['applicant','active','inactive','graduated','dropped'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="program_id" class="form-select form-select-sm">
                    <option value="">All Programs</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->code }}</option>
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
                    <tr><th>Student ID</th><th>Name</th><th>Program</th><th>Year</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="fw-semibold">{{ $student->student_id }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>{{ $student->program->code ?? 'N/A' }}</td>
                            <td>{{ $student->year_level }}</td>
                            <td>
                                @php $colors = ['applicant'=>'warning','active'=>'success','inactive'=>'secondary','graduated'=>'info','dropped'=>'danger']; @endphp
                                <span class="badge bg-{{ $colors[$student->status] ?? 'secondary' }}">{{ ucfirst($student->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('registrar.students.show', $student) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('registrar.students.edit', $student) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                @if($student->status === 'applicant')
                                    <form action="{{ route('registrar.students.approve', $student) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('registrar.students.reject', $student) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $students->links() }}
    </div>
</div>
@endsection
