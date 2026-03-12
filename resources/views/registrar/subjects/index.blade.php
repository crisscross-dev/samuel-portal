@extends('layouts.app')
@section('title', 'Subjects')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-book me-1"></i> Subjects</h6>
        <a href="{{ route('registrar.subjects.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Subject</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search code or name" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Subject</th>
                        <th>Units</th>
                        <th>Programs</th>
                        <th>Sections</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td class="fw-semibold">{{ $subject->code }}</td>
                        <td>
                            <div>{{ $subject->name }}</div>
                            @if($subject->description)
                            <small class="text-muted">{{ $subject->description }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $subject->lecture_units }} Lec</span>
                            <span class="badge bg-secondary">{{ $subject->lab_units }} Lab</span>
                            <span class="badge bg-dark">{{ $subject->totalUnits() }} Total</span>
                        </td>
                        <td><span class="badge bg-info">{{ $subject->programs_count }}</span></td>
                        <td><span class="badge bg-warning text-dark">{{ $subject->section_subjects_count }}</span></td>
                        <td>
                            <span class="badge bg-{{ $subject->is_active ? 'success' : 'secondary' }}">{{ $subject->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td>
                            <a href="{{ route('registrar.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('registrar.subjects.destroy', $subject) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subject?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">No subjects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $subjects->links() }}
        </div>
    </div>
</div>
@endsection