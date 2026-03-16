@extends('layouts.app')
@section('title', 'Interview Scheduler')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Create Interview Slot</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('guidance.interview-slots.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label fw-semibold">Form Type</label>
                        <select name="form_type" class="form-select" required>
                            <option value="">Select type</option>
                            <option value="jhs" {{ old('form_type') === 'jhs' ? 'selected' : '' }}>JHS</option>
                            <option value="shs" {{ old('form_type') === 'shs' ? 'selected' : '' }}>SHS</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Interview Date</label>
                        <input type="date" name="interview_date" class="form-control" value="{{ old('interview_date') }}" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Start Time</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">End Time</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Add Slot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Active Scheduler</h6>
                <div class="d-inline-flex gap-2 align-items-center">
                    <a href="{{ route('guidance.scheduler.logs') }}" class="btn btn-sm btn-outline-dark">View Scheduling Log</a>
                    <span class="small text-muted">Expired slots auto-deactivate</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th>Assigned Applicant</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($slots as $slot)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $slot->form_type === 'shs' ? 'info' : 'primary' }}">{{ strtoupper($slot->form_type) }}</span>
                                </td>
                                <td>
                                    {{ $slot->interview_date->format('M d, Y') }}<br>
                                    <span class="text-muted small">{{ \Illuminate\Support\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Illuminate\Support\Carbon::parse($slot->end_time)->format('h:i A') }}</span>
                                </td>
                                <td>
                                    @if(!$slot->is_active)
                                    <span class="badge bg-secondary">Inactive</span>
                                    @elseif($slot->application)
                                    <span class="badge bg-dark">Occupied</span>
                                    @else
                                    <span class="badge bg-success">Available</span>
                                    @endif
                                </td>
                                <td>
                                    @if($slot->application)
                                    <div class="fw-semibold">{{ $slot->application->fullName() }}</div>
                                    <div class="small text-muted">{{ $slot->application->email }}</div>
                                    @else
                                    <span class="text-muted small">No applicant yet</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form method="POST" action="{{ route('guidance.interview-slots.toggle', $slot) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-{{ $slot->is_active ? 'secondary' : 'success' }}">{{ $slot->is_active ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                        @if(!$slot->application)
                                        <form method="POST" action="{{ route('guidance.interview-slots.destroy', $slot) }}" onsubmit="return confirm('Delete this interview slot?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No interview slots created yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($slots->hasPages())
            <div class="card-footer">{{ $slots->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection