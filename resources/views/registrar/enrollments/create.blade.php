@extends('layouts.app')
@section('title', 'Create Enrollment')

@section('content')
@if(!$semester)
    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i> No active semester. Ask the Admin to set one before enrolling.</div>
@else
<div class="card" style="max-width: 800px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-journal-plus me-1"></i> Enroll Student - {{ $semester->academicYear->name ?? '' }} {{ $semester->name }}</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.enrollments.store') }}">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $semester->id }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Select Student</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Choose Student --</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->student_id ?? '' }} - {{ $student->user->name }} ({{ $student->program->code ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Assign to Section</label>
                <select name="section_id" class="form-select" required>
                    <option value="">-- Choose Section --</option>
                    @foreach($sections->groupBy(fn($s) => $s->gradeLevel->department->code ?? 'Other') as $deptCode => $deptSections)
                        <optgroup label="{{ $deptCode }}">
                            @foreach($deptSections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}
                                    {{ $section->students_count >= $section->max_students ? 'disabled' : '' }}>
                                    {{ $section->gradeLevel->name ?? '' }} — {{ $section->name }}
                                    ({{ $section->students_count }}/{{ $section->max_students }} students)
                                    {{ $section->adviser?->user?->name ? '| Adviser: ' . $section->adviser->user->name : '' }}
                                    {{ $section->students_count >= $section->max_students ? ' [FULL]' : '' }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <small class="text-muted">Student will be auto-enrolled in all subjects assigned to this section.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Enrollment</button>
                <a href="{{ route('registrar.enrollments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
