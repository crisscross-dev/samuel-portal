@extends('layouts.app')
@section('title', 'Grade Levels')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-list-ol me-1"></i> Grade Levels</h6>
        <a href="{{ route('registrar.grade-levels.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Grade Level</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->code }} — {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Department</th><th>Name</th><th>Order</th><th>Sections</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($gradeLevels as $gl)
                        <tr>
                            <td><span class="badge bg-dark">{{ $gl->department->code ?? '' }}</span></td>
                            <td class="fw-semibold">{{ $gl->name }}</td>
                            <td>{{ $gl->level_order }}</td>
                            <td><span class="badge bg-primary">{{ $gl->sections_count }}</span></td>
                            <td>
                                <span class="badge bg-{{ $gl->is_active ? 'success' : 'secondary' }}">{{ $gl->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('registrar.grade-levels.edit', $gl) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('registrar.grade-levels.destroy', $gl) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No grade levels found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
