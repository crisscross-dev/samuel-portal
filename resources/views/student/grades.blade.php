@extends('layouts.app')
@section('title', 'My Grades')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-bar-chart-line me-1"></i> Academic Grades</h5>
    </div>
    <div class="card-body">
        @forelse($grades as $semLabel => $gradeGroup)
            <h6 class="mt-3 mb-2 text-primary"><i class="bi bi-calendar2 me-1"></i> {{ $semLabel }}</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Subject</th>
                            <th class="text-center">Units</th>
                            <th class="text-center">Final Grade</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeGroup as $g)
                            <tr>
                                <td><strong>{{ $g->enrollmentSubject->subject->code ?? '' }}</strong></td>
                                <td>{{ $g->enrollmentSubject->subject->name ?? '' }}</td>
                                <td class="text-center">{{ $g->enrollmentSubject->subject->totalUnits() ?? '-' }}</td>
                                <td class="text-center fw-bold fs-6">{{ number_format($g->final_grade, 2) }}</td>
                                <td class="text-center">
                                    @if($g->remarks === 'passed')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Passed</span>
                                    @elseif($g->remarks === 'failed')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Failed</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($g->remarks) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="bi bi-file-earmark-x fs-1 d-block mb-2"></i>
                No finalized grades available yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
