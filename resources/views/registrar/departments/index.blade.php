@extends('layouts.app')
@section('title', 'Departments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-building me-1"></i> Departments</h6>
        <a href="{{ route('registrar.departments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Department</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Code</th><th>Name</th><th>Department Head</th><th>Description</th><th>Grade Levels</th><th>Faculty</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr>
                            <td class="fw-semibold">{{ $dept->code }}</td>
                            <td>{{ $dept->name }}</td>
                            <td>
                                @if($dept->headFaculty)
                                    <span class="badge bg-primary"><i class="bi bi-person-badge me-1"></i>{{ $dept->headFaculty->user->name ?? '—' }}</span>
                                @else
                                    <span class="text-muted">— Not assigned —</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $dept->description ?? '—' }}</td>
                            <td><span class="badge bg-primary">{{ $dept->grade_levels_count }}</span></td>
                            <td><span class="badge bg-info">{{ $dept->faculty_count }}</span></td>
                            <td>
                                <span class="badge bg-{{ $dept->is_active ? 'success' : 'secondary' }}">{{ $dept->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('registrar.departments.edit', $dept) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('registrar.departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">No departments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
