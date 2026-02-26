@extends('layouts.app')
@section('title', 'Encode Grades')

@section('content')
{{-- Section-Subject Info Header --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-card-checklist me-1"></i>
            Grade Encoding: {{ $sectionSubject->subject->code }} — {{ $sectionSubject->subject->name }}
            | Section: {{ $sectionSubject->section->name }}
        </h6>
        <div class="d-flex gap-1 flex-wrap">
            @if(isset($stats))
                <span class="badge bg-secondary">Total: {{ $stats['total'] }}</span>
                <span class="badge bg-info">Encoded: {{ $stats['encoded'] }}</span>
                <span class="badge bg-success">Finalized: {{ $stats['finalized'] }}</span>
                <span class="badge bg-warning text-dark">Draft: {{ $stats['draft'] }}</span>
                <span class="badge bg-danger">Missing: {{ $stats['missing'] ?? 0 }}</span>
            @endif
        </div>
    </div>
    <div class="card-body py-2">
        <small class="text-muted">
            <i class="bi bi-building me-1"></i> {{ $sectionSubject->section->gradeLevel->department->name ?? '' }}
            &nbsp;|&nbsp; <i class="bi bi-mortarboard me-1"></i> {{ $sectionSubject->section->gradeLevel->name ?? '' }}
            &nbsp;|&nbsp; <i class="bi bi-calendar me-1"></i> {{ $sectionSubject->section->academicYear->name ?? '' }}
            &nbsp;|&nbsp; <i class="bi bi-clock me-1"></i> {{ $sectionSubject->schedule ?? 'TBA' }}
            &nbsp;|&nbsp; <i class="bi bi-geo-alt me-1"></i> {{ $sectionSubject->room ?? 'TBA' }}
        </small>
    </div>
</div>

{{-- Grade Statistics & Tools --}}
@if(isset($stats) && $stats['encoded'] > 0)
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-bar-chart"></i></div>
                <div><div class="text-muted small">Average</div><div class="fw-bold fs-5">{{ number_format($stats['average'] ?? 0, 2) }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-arrow-up-circle"></i></div>
                <div><div class="text-muted small">Highest</div><div class="fw-bold fs-5">{{ number_format($stats['highest'] ?? 0, 2) }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-arrow-down-circle"></i></div>
                <div><div class="text-muted small">Lowest</div><div class="fw-bold fs-5">{{ number_format($stats['lowest'] ?? 0, 2) }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-exclamation-triangle"></i></div>
                <div><div class="text-muted small">Missing</div><div class="fw-bold fs-5">{{ $stats['missing'] ?? 0 }}</div></div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Import & Audit Tools --}}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-tools me-1"></i> Grade Tools</h6>
        <a href="{{ route('faculty.grades.audit', $sectionSubject) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-clock-history me-1"></i> View Audit Log
        </a>
    </div>
    <div class="card-body">
        <div class="row align-items-end g-3">
            <div class="col-md-8">
                <form method="POST" action="{{ route('faculty.grades.import', $sectionSubject) }}" enctype="multipart/form-data" class="d-flex align-items-end gap-2">
                    @csrf
                    <div class="flex-grow-1">
                        <label class="form-label fw-semibold mb-1"><i class="bi bi-file-earmark-arrow-up me-1"></i> Bulk CSV Import</label>
                        <input type="file" name="csv_file" class="form-control form-control-sm" accept=".csv,.txt" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload me-1"></i> Import</button>
                </form>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('faculty.grades.template', $sectionSubject) }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-download me-1"></i> Download CSV Template
                </a>
            </div>
        </div>
        <div class="mt-2"><small class="text-muted">CSV must contain columns: <code>student_id</code> and <code>final_grade</code>. Only draft grades will be updated; finalized grades are skipped.</small></div>
    </div>
</div>

{{-- Grade Encoding Form --}}
<form method="POST" action="{{ route('faculty.grades.update', $sectionSubject) }}" id="gradeForm">
    @csrf @method('PUT')
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th style="width:150px;" class="text-center">Final Grade</th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollmentSubjects as $i => $es)
                            @php $grade = $es->grade; @endphp
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td><strong>{{ $es->enrollment->student->student_id ?? '' }}</strong></td>
                                <td>{{ $es->enrollment->student->user->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if($grade && $grade->is_finalized)
                                        <span class="fw-bold fs-5">{{ number_format($grade->final_grade, 2) }}</span>
                                    @elseif($grade)
                                        <input type="number"
                                               name="grades[{{ $grade->id }}][final_grade]"
                                               class="form-control form-control-sm text-center grade-input"
                                               step="0.01" min="0" max="100"
                                               placeholder="0 – 100"
                                               value="{{ old("grades.{$grade->id}.final_grade", $grade->final_grade) }}">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($grade && $grade->final_grade !== null)
                                        @if($grade->remarks === 'passed')
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Passed</span>
                                        @elseif($grade->remarks === 'failed')
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Failed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($grade->remarks) }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($grade?->is_finalized)
                                        <span class="badge bg-success"><i class="bi bi-lock-fill me-1"></i>Finalized</span>
                                    @elseif($grade && $grade->final_grade !== null)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-pencil me-1"></i>Draft</span>
                                    @elseif($grade)
                                        <span class="badge bg-light text-dark border">Not Encoded</span>
                                    @else
                                        <span class="badge bg-secondary">No Record</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i> No students enrolled for this subject.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($enrollmentSubjects->isNotEmpty())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save Draft
                    </button>
                    <a href="{{ route('faculty.sections.show', $sectionSubject) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Class List
                    </a>
                </div>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#finalizeModal"
                    @if(!isset($stats) || $stats['encoded'] === 0) disabled @endif>
                    <i class="bi bi-lock me-1"></i> Finalize Grades
                </button>
            </div>
        @endif
    </div>
</form>

{{-- Finalization Confirmation Modal --}}
<div class="modal fade" id="finalizeModal" tabindex="-1" aria-labelledby="finalizeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="finalizeModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i> Confirm Grade Finalization
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-shield-lock me-1"></i>
                    <strong>Warning:</strong> This action cannot be undone by faculty.
                </div>
                <p>You are about to finalize grades for:</p>
                <ul class="mb-3">
                    <li><strong>Subject:</strong> {{ $sectionSubject->subject->code }} — {{ $sectionSubject->subject->name }}</li>
                    <li><strong>Section:</strong> {{ $sectionSubject->section->name }}</li>
                    <li><strong>Draft Grades:</strong> {{ $stats['draft'] ?? 0 }}</li>
                </ul>
                <p class="mb-0">Once finalized, grades will be <strong>locked</strong> and visible to students. Only an Admin, Registrar, or Department Head can reopen them.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('faculty.grades.finalize', $sectionSubject) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-lock-fill me-1"></i> Yes, Finalize Grades
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Auto-compute remarks preview on input --}}
@push('scripts')
<script>
document.querySelectorAll('.grade-input').forEach(input => {
    input.addEventListener('input', function() {
        const row = this.closest('tr');
        const remarksCell = row.querySelectorAll('td')[4]; // remarks column
        const val = parseFloat(this.value);
        if (isNaN(val) || this.value === '') {
            remarksCell.innerHTML = '<span class="text-muted">—</span>';
        } else if (val >= 75) {
            remarksCell.innerHTML = '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Passed</span>';
        } else {
            remarksCell.innerHTML = '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Failed</span>';
        }
    });
});
</script>
@endpush
@endsection
