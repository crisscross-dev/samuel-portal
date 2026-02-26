@extends('layouts.app')
@section('title', 'Class List')

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-collection me-1"></i> {{ $sectionSubject->subject->code }} — {{ $sectionSubject->subject->name }} | Section: {{ $sectionSubject->section->name }}</h6>
    </div>
    <div class="card-body py-2">
        <span class="text-muted">Department: {{ $sectionSubject->section->gradeLevel->department->code ?? '' }}</span> |
        <span class="text-muted">Grade Level: {{ $sectionSubject->section->gradeLevel->name ?? '' }}</span> |
        <span class="text-muted">Schedule: {{ $sectionSubject->schedule ?? 'TBA' }}</span> |
        <span class="text-muted">Room: {{ $sectionSubject->room ?? 'TBA' }}</span> |
        <span class="text-muted">AY: {{ $sectionSubject->section->academicYear->name ?? '' }}</span>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Enrolled Students</h6>
        <a href="{{ route('faculty.grades.edit', $sectionSubject) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil me-1"></i> Encode Grades</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Student ID</th><th>Name</th><th>Email</th><th>Grade Status</th></tr>
                </thead>
                <tbody>
                    @php $enrollmentSubjects = $sectionSubject->section->enrollmentSubjects ?? collect(); @endphp
                    @forelse($enrollmentSubjects as $i => $es)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $es->enrollment->student->student_id ?? '' }}</td>
                            <td>{{ $es->enrollment->student->user->name ?? 'N/A' }}</td>
                            <td>{{ $es->enrollment->student->user->email ?? '' }}</td>
                            <td>
                                @if($es->grade)
                                    @if($es->grade->is_finalized)
                                        <span class="badge bg-success">Finalized: {{ number_format($es->grade->final_grade, 2) }}</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">No Grade</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No enrolled students.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
