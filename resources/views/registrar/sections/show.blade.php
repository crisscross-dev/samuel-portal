@extends('layouts.app')
@section('title', 'Section Details')

@section('content')
<div class="row g-4">
    {{-- Section Info --}}
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-collection me-1"></i> {{ $section->displayName() }}</h6>
                <a href="{{ route('registrar.sections.edit', $section) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Department</th>
                        <td>{{ $section->gradeLevel->department->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Grade Level</th>
                        <td>{{ $section->gradeLevel->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Academic Year</th>
                        <td>{{ $section->academicYear->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Adviser</th>
                        <td>{{ $section->adviser?->user?->name ?? 'TBA' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Students</th>
                        <td>
                            <span class="badge bg-{{ $section->students->count() >= $section->max_students ? 'danger' : 'primary' }}">
                                {{ $section->students->count() }}/{{ $section->max_students }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Students List --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-people me-1"></i> Assigned Students</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($section->students as $i => $student)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->user->name ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No students assigned.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Subjects --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-book me-1"></i> Section Subjects ({{ $section->sectionSubjects->count() }})</h6>
                <a href="{{ route('registrar.sections.edit', $section) }}#subjects" class="btn btn-sm btn-outline-success"><i class="bi bi-plus-lg me-1"></i> Manage Subjects</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Faculty</th>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Units</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($section->sectionSubjects as $ss)
                            <tr>
                                <td class="fw-semibold">{{ $ss->subject->code }}</td>
                                <td>{{ $ss->subject->name }}</td>
                                <td>{{ $ss->faculty?->user?->name ?? 'TBA' }}</td>
                                <td>{{ $ss->schedule ?? 'TBA' }}</td>
                                <td>{{ $ss->room ?? 'TBA' }}</td>
                                <td>{{ $ss->subject->totalUnits() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No subjects assigned to this section.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($section->sectionSubjects->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total Units:</td>
                                <td class="fw-bold">{{ $section->sectionSubjects->sum(fn($ss) => $ss->subject->totalUnits()) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection