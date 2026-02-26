@extends('layouts.app')
@section('title', 'Faculty Dashboard')

@section('content')
@if($semester)
    <div class="alert alert-info py-2 mb-4">
        <i class="bi bi-calendar3 me-1"></i> Current Semester: <strong>{{ $semester->academicYear->name ?? '' }} - {{ $semester->name }}</strong>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-collection"></i></div>
                <div>
                    <div class="text-muted small">My Sections</div>
                    <div class="fw-bold fs-5">{{ $stats['total_sections'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-people"></i></div>
                <div>
                    <div class="text-muted small">Total Students</div>
                    <div class="fw-bold fs-5">{{ $stats['total_students'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="text-muted small">Pending Grades</div>
                    <div class="fw-bold fs-5">{{ $stats['pending_grades'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-collection me-1"></i> My Teaching Loads</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Subject</th><th>Section</th><th>Grade Level</th><th>Schedule</th><th>Room</th><th>Students</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($loads as $ss)
                        <tr>
                            <td><strong>{{ $ss->subject->code }}</strong><br><small class="text-muted">{{ $ss->subject->name }}</small></td>
                            <td>{{ $ss->section->name }}</td>
                            <td>{{ $ss->section->gradeLevel->name ?? '' }}<br><small class="text-muted">{{ $ss->section->gradeLevel->department->name ?? '' }}</small></td>
                            <td>{{ $ss->schedule ?? 'TBA' }}</td>
                            <td>{{ $ss->room ?? 'TBA' }}</td>
                            <td><span class="badge bg-primary">{{ $ss->enrolledCount() }}</span></td>
                            <td>
                                <a href="{{ route('faculty.sections.show', $ss) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye me-1"></i>Class List</a>
                                <a href="{{ route('faculty.grades.edit', $ss) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Grades</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No teaching loads assigned for this academic year.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
