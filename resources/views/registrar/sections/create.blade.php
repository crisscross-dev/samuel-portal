@extends('layouts.app')
@section('title', 'Create Section')

@section('content')
<div class="card" style="max-width: 900px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-plus-lg me-1"></i> Create Section</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.sections.store') }}">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Section Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., Section A" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Grade Level</label>
                    <select name="grade_level_id" class="form-select" required>
                        <option value="">Select Grade Level</option>
                        @foreach($gradeLevels as $gl)
                            <option value="{{ $gl->id }}" {{ old('grade_level_id') == $gl->id ? 'selected' : '' }}>
                                {{ $gl->department->code ?? '' }} — {{ $gl->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Academic Year</label>
                    <select name="academic_year_id" class="form-select" required>
                        <option value="">Select AY</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ old('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                {{ $ay->name }} {{ $ay->is_active ? '(Active)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Section Adviser</label>
                    <select name="adviser_id" class="form-select">
                        <option value="">TBA</option>
                        @foreach($faculty as $f)
                            <option value="{{ $f->id }}" {{ old('adviser_id') == $f->id ? 'selected' : '' }}>{{ $f->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Max Students</label>
                    <input type="number" name="max_students" class="form-control" value="{{ old('max_students', 40) }}" min="1" max="100" required>
                </div>
            </div>

            {{-- Section Subjects --}}
            <h6 class="fw-bold mb-3"><i class="bi bi-book me-1"></i> Section Subjects</h6>
            <div id="subjects-container">
                @if(old('subjects'))
                    @foreach(old('subjects') as $i => $subj)
                        <div class="row g-2 mb-2 subject-row">
                            <div class="col-md-4">
                                <select name="subjects[{{ $i }}][subject_id]" class="form-select form-select-sm" required>
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}" {{ ($subj['subject_id'] ?? '') == $s->id ? 'selected' : '' }}>{{ $s->code }} — {{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="subjects[{{ $i }}][faculty_id]" class="form-select form-select-sm">
                                    <option value="">Faculty TBA</option>
                                    @foreach($faculty as $f)
                                        <option value="{{ $f->id }}" {{ ($subj['faculty_id'] ?? '') == $f->id ? 'selected' : '' }}>{{ $f->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="subjects[{{ $i }}][schedule]" class="form-control form-control-sm" placeholder="Schedule" value="{{ $subj['schedule'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="subjects[{{ $i }}][room]" class="form-control form-control-sm" placeholder="Room" value="{{ $subj['room'] ?? '' }}">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-subject"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-sm btn-outline-success mb-4" id="add-subject">
                <i class="bi bi-plus-lg me-1"></i> Add Subject
            </button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Section</button>
                <a href="{{ route('registrar.sections.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let subjectIndex = {{ old('subjects') ? count(old('subjects')) : 0 }};
    const subjectsJson = @json($subjects->map(fn($s) => ['id' => $s->id, 'label' => $s->code . ' — ' . $s->name]));
    const facultyJson = @json($faculty->map(fn($f) => ['id' => $f->id, 'label' => $f->user->name]));

    document.getElementById('add-subject').addEventListener('click', function() {
        const container = document.getElementById('subjects-container');
        let subjectOpts = '<option value="">Select Subject</option>';
        subjectsJson.forEach(s => { subjectOpts += `<option value="${s.id}">${s.label}</option>`; });
        let facultyOpts = '<option value="">Faculty TBA</option>';
        facultyJson.forEach(f => { facultyOpts += `<option value="${f.id}">${f.label}</option>`; });

        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 subject-row';
        row.innerHTML = `
            <div class="col-md-4"><select name="subjects[${subjectIndex}][subject_id]" class="form-select form-select-sm" required>${subjectOpts}</select></div>
            <div class="col-md-3"><select name="subjects[${subjectIndex}][faculty_id]" class="form-select form-select-sm">${facultyOpts}</select></div>
            <div class="col-md-2"><input type="text" name="subjects[${subjectIndex}][schedule]" class="form-control form-control-sm" placeholder="Schedule"></div>
            <div class="col-md-2"><input type="text" name="subjects[${subjectIndex}][room]" class="form-control form-control-sm" placeholder="Room"></div>
            <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger w-100 remove-subject"><i class="bi bi-x-lg"></i></button></div>
        `;
        container.appendChild(row);
        subjectIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-subject')) {
            e.target.closest('.subject-row').remove();
        }
    });
</script>
@endpush
@endsection
