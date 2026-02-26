@extends('layouts.app')
@section('title', 'Edit Academic Year')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0">Edit Academic Year: {{ $academicYear->name }}</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.academic-years.update', $academicYear) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $academicYear->name) }}" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Set as Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.academic-years.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
