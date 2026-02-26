@extends('layouts.app')
@section('title', 'My Schedule')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-calendar3 me-1"></i> Class Schedule</h5>
        @if($semester)
            <span class="badge bg-primary fs-6">{{ $semester->academicYear->name ?? '' }} — {{ $semester->name }}</span>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Subject</th>
                        <th>Section</th>
                        <th>Schedule</th>
                        <th>Room</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedule as $i => $row)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $row['subject_code'] }}</strong></td>
                            <td>{{ $row['subject_name'] }}</td>
                            <td>{{ $row['section_name'] }}</td>
                            <td><i class="bi bi-clock me-1 text-muted"></i>{{ $row['schedule'] ?? 'TBA' }}</td>
                            <td><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $row['room'] ?? 'TBA' }}</td>
                            <td><i class="bi bi-person me-1 text-muted"></i>{{ $row['instructor'] ?? 'TBA' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4"><i class="bi bi-calendar-x fs-1 d-block mb-2"></i> No schedule found for the current semester.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
