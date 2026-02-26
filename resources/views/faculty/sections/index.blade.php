@extends('layouts.app')
@section('title', 'Teaching Loads')

@section('content')
<div class="card">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-collection me-1"></i> My Teaching Loads</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Subject</th><th>Section</th><th>Department</th><th>Grade Level</th><th>Schedule</th><th>Room</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($sectionSubjects as $ss)
                        <tr>
                            <td><strong>{{ $ss->subject->code }}</strong> — {{ $ss->subject->name }}</td>
                            <td>{{ $ss->section->name }}</td>
                            <td><span class="badge bg-dark">{{ $ss->section->gradeLevel->department->code ?? '' }}</span></td>
                            <td>{{ $ss->section->gradeLevel->name ?? '' }}</td>
                            <td>{{ $ss->schedule ?? 'TBA' }}</td>
                            <td>{{ $ss->room ?? 'TBA' }}</td>
                            <td>
                                <a href="{{ route('faculty.sections.show', $ss) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('faculty.grades.edit', $ss) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Grades</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No teaching loads assigned.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
