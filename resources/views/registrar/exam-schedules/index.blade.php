@extends('layouts.app')
@section('title', 'Exam Schedules')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-event me-2"></i>Exam Schedules</h5>
        <small class="text-muted">Manage entrance exam dates, time slots, and seat capacity.</small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
        <i class="bi bi-plus-circle me-1"></i> Add Schedule
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show py-2" role="alert">
    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if($errors->has('delete'))
<div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
    <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first('delete') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        @if($schedules->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
            No exam schedules configured yet.<br>
            <small>Add a schedule to let applicants pick an exam date.</small>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th class="text-center">Capacity</th>
                        <th class="text-center">Booked</th>
                        <th class="text-center">Available</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $sched)
                    @php
                    $booked = $sched->applications_count;
                    $available = max(0, $sched->max_capacity - $booked);
                    $full = $available === 0;
                    $pct = $sched->max_capacity > 0 ? round($booked / $sched->max_capacity * 100) : 0;
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $sched->exam_date->format('l') }}</div>
                            <div class="text-muted small">{{ $sched->exam_date->format('F j, Y') }}</div>
                        </td>
                        <td>
                            @if($sched->time_slot === '9am')
                            <span class="badge" style="background:#fef9c3; color:#92400e; border:1px solid #fde68a">
                                <i class="bi bi-sun me-1"></i>9:00 AM – Morning
                            </span>
                            @else
                            <span class="badge" style="background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe">
                                <i class="bi bi-cloud-sun me-1"></i>1:00 PM – Afternoon
                            </span>
                            @endif
                        </td>
                        <td class="text-center">{{ $sched->max_capacity }}</td>
                        <td class="text-center">
                            <span class="fw-semibold {{ $booked > 0 ? 'text-primary' : 'text-muted' }}">{{ $booked }}</span>
                        </td>
                        <td class="text-center">
                            @if($full)
                            <span class="badge bg-danger">Full</span>
                            @elseif($available <= 5)
                                <span class="badge bg-warning text-dark">{{ $available }} left</span>
                                @else
                                <span class="badge bg-success">{{ $available }} left</span>
                                @endif
                        </td>
                        <td class="text-center">
                            @if($sched->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                {{-- Edit --}}
                                <button class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $sched->id }}"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Toggle active --}}
                                <form method="POST" action="{{ route('registrar.exam-schedules.toggle', $sched) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-{{ $sched->is_active ? 'warning' : 'success' }}"
                                        title="{{ $sched->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $sched->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                    </button>
                                </form>

                                {{-- Delete --}}
                                @if($booked === 0)
                                <form method="POST" action="{{ route('registrar.exam-schedules.destroy', $sched) }}"
                                    onsubmit="return confirm('Delete this schedule?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-sm btn-outline-danger" disabled title="Cannot delete — has bookings">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Add Schedule Modal --}}
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.exam-schedules.store') }}">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title"><i class="bi bi-calendar-plus me-1"></i> Add Exam Schedule</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($errors->has('exam_date'))
                    <div class="alert alert-danger py-2 small">{{ $errors->first('exam_date') }}</div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Exam Date <span class="text-danger">*</span></label>
                        <input type="date" name="exam_date" class="form-control @error('exam_date') is-invalid @enderror"
                            value="{{ old('exam_date') }}" required>
                        @error('exam_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Time Slot <span class="text-danger">*</span></label>
                        <select name="time_slot" class="form-select @error('time_slot') is-invalid @enderror" required>
                            <option value="">— Select —</option>
                            <option value="9am" {{ old('time_slot') === '9am' ? 'selected' : '' }}>9:00 AM – Morning Session</option>
                            <option value="1pm" {{ old('time_slot') === '1pm' ? 'selected' : '' }}>1:00 PM – Afternoon Session</option>
                        </select>
                        @error('time_slot')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Max Capacity <span class="text-danger">*</span></label>
                        <input type="number" name="max_capacity" class="form-control @error('max_capacity') is-invalid @enderror"
                            value="{{ old('max_capacity', 30) }}" min="1" max="500" required>
                        @error('max_capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Add Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modals (one per schedule) --}}
@foreach($schedules as $sched)
<div class="modal fade" id="editModal{{ $sched->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('registrar.exam-schedules.update', $sched) }}">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h6 class="modal-title"><i class="bi bi-pencil me-1"></i> Edit Schedule</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Exam Date</label>
                        <input type="date" name="exam_date" class="form-control"
                            value="{{ $sched->exam_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Time Slot</label>
                        <select name="time_slot" class="form-select" required>
                            <option value="9am" {{ $sched->time_slot === '9am' ? 'selected' : '' }}>9:00 AM – Morning Session</option>
                            <option value="1pm" {{ $sched->time_slot === '1pm' ? 'selected' : '' }}>1:00 PM – Afternoon Session</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Max Capacity</label>
                        <input type="number" name="max_capacity" class="form-control"
                            value="{{ $sched->max_capacity }}" min="{{ $sched->applications_count }}" max="500" required>
                        <div class="form-text">Currently {{ $sched->applications_count }} applicant(s) booked. Capacity cannot be lower than this.</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ $sched->is_active ? 'selected' : '' }}>Active – visible to applicants</option>
                            <option value="0" {{ !$sched->is_active ? 'selected' : '' }}>Inactive – hidden from applicants</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection