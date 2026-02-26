@extends('layouts.app')
@section('title', 'Department Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-building me-1"></i> Department Management</h6>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Department</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Department Head</th>
                        <th>Grade Levels</th>
                        <th>Faculty</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                        <tr class="{{ !$dept->is_active ? 'table-secondary' : '' }}">
                            <td class="fw-semibold">{{ $dept->code }}</td>
                            <td>{{ $dept->name }}</td>
                            <td><small class="text-muted">{{ $dept->description ?? '—' }}</small></td>
                            <td>
                                @if($dept->headFaculty)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill me-1"></i>{{ $dept->headFaculty->user->name }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $dept->grade_levels_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $dept->faculty_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $dept->is_active ? 'success' : 'danger' }}">
                                    {{ $dept->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.departments.edit', $dept) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-building fs-1 d-block mb-2"></i>
                                No departments found. <a href="{{ route('admin.departments.create') }}">Create one now.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
