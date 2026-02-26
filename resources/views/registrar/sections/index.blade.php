@extends('layouts.app')
@section('title', 'Sections')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-collection me-1"></i> Sections</h6>
        <a href="{{ route('registrar.sections.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Add Section</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="grade_level_id" class="form-select form-select-sm">
                    <option value="">All Grade Levels</option>
                    @foreach($gradeLevels as $gl)
                        <option value="{{ $gl->id }}" {{ request('grade_level_id') == $gl->id ? 'selected' : '' }}>
                            {{ $gl->department->code ?? '' }} — {{ $gl->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="academic_year_id" class="form-select form-select-sm">
                    <option value="">All Academic Years</option>
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                            {{ $ay->name }} {{ $ay->is_active ? '(Active)' : '' }}
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
                    <tr><th>Section</th><th>Department</th><th>Grade Level</th><th>Academic Year</th><th>Adviser</th><th>Subjects</th><th>Students</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($sections as $sec)
                        <tr>
                            <td class="fw-semibold">{{ $sec->name }}</td>
                            <td><span class="badge bg-dark">{{ $sec->gradeLevel->department->code ?? '' }}</span></td>
                            <td>{{ $sec->gradeLevel->name ?? '' }}</td>
                            <td>{{ $sec->academicYear->name ?? '' }}</td>
                            <td>{{ $sec->adviser?->user?->name ?? 'TBA' }}</td>
                            <td><span class="badge bg-info">{{ $sec->section_subjects_count }}</span></td>
                            <td>
                                <span class="badge bg-{{ $sec->students_count >= $sec->max_students ? 'danger' : 'primary' }}">
                                    {{ $sec->students_count }}/{{ $sec->max_students }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('registrar.sections.show', $sec) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('registrar.sections.edit', $sec) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('registrar.sections.destroy', $sec) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this section?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">No sections found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $sections->links() }}
    </div>
</div>
@endsection
