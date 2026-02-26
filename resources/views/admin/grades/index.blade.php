@extends('layouts.app')
@section('title', 'Grade Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-clipboard-data me-1"></i> Grade Management</h5>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1 small">Semester</label>
                <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Semesters</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ request('semester_id') == $sem->id ? 'selected' : '' }}>
                            {{ $sem->academicYear->name }} — {{ $sem->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1 small">Section-Subject</label>
                <select name="section_subject_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($sectionSubjects as $ss)
                        <option value="{{ $ss->id }}" {{ request('section_subject_id') == $ss->id ? 'selected' : '' }}>
                            {{ $ss->subject->code ?? '' }} — {{ $ss->section->name ?? '' }} ({{ $ss->section->gradeLevel->name ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1 small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="finalized" {{ request('status') === 'finalized' ? 'selected' : '' }}>Finalized</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
            @if(request()->hasAny(['semester_id', 'section_subject_id', 'status']))
                <div class="col-md-2">
                    @php
                        $currentRoute = request()->routeIs('admin.*') ? route('admin.grades.index') : route('registrar.grades.index');
                    @endphp
                    <a href="{{ $currentRoute }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i> Clear</a>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- Bulk Reopen for Section-Subject --}}
@if(request('section_subject_id'))
    @php
        $selectedSS = $sectionSubjects->firstWhere('id', request('section_subject_id'));
        $reopenRoute = request()->routeIs('admin.*')
            ? route('admin.grades.reopen-section-subject', request('section_subject_id'))
            : route('registrar.grades.reopen-section-subject', request('section_subject_id'));
    @endphp
    @if($selectedSS)
        <div class="alert alert-info d-flex justify-content-between align-items-center py-2">
            <span><i class="bi bi-info-circle me-1"></i> Viewing grades for: <strong>{{ $selectedSS->subject->code ?? '' }} — {{ $selectedSS->section->name ?? '' }}</strong></span>
            <form method="POST" action="{{ $reopenRoute }}" onsubmit="return confirm('Reopen ALL finalized grades for this subject-section?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-unlock me-1"></i> Reopen All Grades</button>
            </form>
        </div>
    @endif
@endif

{{-- Grades Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Section</th>
                        <th>Faculty</th>
                        <th class="text-center">Final Grade</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width:120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $grade->student->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $grade->student->student_id ?? '' }}</small>
                            </td>
                            <td>
                                <strong>{{ $grade->enrollmentSubject->subject->code ?? '' }}</strong>
                                <br><small>{{ $grade->enrollmentSubject->subject->name ?? '' }}</small>
                            </td>
                            <td>
                                {{ $grade->enrollmentSubject->section->name ?? '-' }}
                                @if($grade->enrollmentSubject->section->gradeLevel ?? false)
                                    <br><small class="text-muted">{{ $grade->enrollmentSubject->section->gradeLevel->name }}</small>
                                @endif
                            </td>
                            <td>{{ $grade->faculty->user->name ?? '-' }}</td>
                            <td class="text-center fw-bold">
                                {{ $grade->final_grade !== null ? number_format($grade->final_grade, 2) : '—' }}
                            </td>
                            <td class="text-center">
                                @if($grade->remarks === 'passed')
                                    <span class="badge bg-success">Passed</span>
                                @elseif($grade->remarks === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($grade->remarks) }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($grade->is_finalized)
                                    <span class="badge bg-success"><i class="bi bi-lock-fill me-1"></i>Finalized</span>
                                    <br><small class="text-muted">{{ $grade->finalized_at?->format('M d, Y') }}</small>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-pencil me-1"></i>Draft</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($grade->is_finalized)
                                    @php
                                        $reopenGradeRoute = request()->routeIs('admin.*')
                                            ? route('admin.grades.reopen', $grade)
                                            : route('registrar.grades.reopen', $grade);
                                    @endphp
                                    <form method="POST" action="{{ $reopenGradeRoute }}"
                                          onsubmit="return confirm('Reopen this grade for editing?')" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Reopen for editing">
                                            <i class="bi bi-unlock"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Editable</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i> No grade records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($grades->hasPages())
        <div class="card-footer">{{ $grades->links() }}</div>
    @endif
</div>
@endsection
