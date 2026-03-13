@extends('layouts.app')
@section('title', 'Create Enrollment')

@section('content')
@if(!$semester)
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i> No active semester. Ask the Admin to set one before enrolling.</div>
@else
@if($application)
<div class="card mb-4" style="max-width: 800px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-person-vcard me-1"></i> Enrollment Candidate</h6>
    </div>
    <div class="card-body">
        <div class="row g-3 small">
            <div class="col-md-6"><strong>Applicant:</strong><br>{{ $application->fullName() }}</div>
            <div class="col-md-6"><strong>Email:</strong><br>{{ $application->email }}</div>
            <div class="col-md-6"><strong>Program:</strong><br>{{ $application->program->name ?? 'N/A' }}</div>
            <div class="col-md-6"><strong>Year Level:</strong><br>{{ $application->year_level }}</div>
        </div>
        <div class="alert alert-info mt-3 mb-0 small">
            Saving this enrollment will create or reuse the student's portal account, create the student profile if needed, and forward the admission record to Cashier for payment.
        </div>
    </div>
</div>
@endif
<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-journal-plus me-1"></i> Enroll Student - {{ $semester->academicYear->name ?? '' }} {{ $semester->name }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.enrollments.store') }}">
            @csrf
            <input type="hidden" name="semester_id" value="{{ $semester->id }}">
            @if($application)
            <input type="hidden" name="application_id" value="{{ $application->id }}">
            @endif

            @if(!$application)
            <div class="mb-3">
                <label class="form-label fw-semibold">Select Student</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Choose Student --</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $selectedStudent?->id) == $student->id ? 'selected' : '' }}>
                        {{ $student->student_id ?? '' }} - {{ $student->user->name }} ({{ $student->program->code ?? 'N/A' }})
                    </option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="mb-3">
                <label class="form-label fw-semibold">Student Record</label>
                <div class="form-control bg-light">
                    {{ $selectedStudent?->student_id ? $selectedStudent->student_id . ' - ' : '' }}{{ $application->fullName() }}
                </div>
                <small class="text-muted">The student account will be created automatically from the admission application if it does not exist yet.</small>
            </div>
            @endif

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
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> {{ $application ? 'Create Enrollment and Forward to Cashier' : 'Create Enrollment' }}</button>
                <a href="{{ route('registrar.enrollments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endif
@endsection