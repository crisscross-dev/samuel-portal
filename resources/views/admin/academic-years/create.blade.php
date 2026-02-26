@extends('layouts.app')
@section('title', 'Create Academic Year')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0">Create Academic Year</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.academic-years.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., 2025-2026" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Set as Active</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="auto_semesters" value="1" class="form-check-input" id="auto_semesters" checked>
                <label class="form-check-label" for="auto_semesters">Auto-create 1st & 2nd Semesters</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
