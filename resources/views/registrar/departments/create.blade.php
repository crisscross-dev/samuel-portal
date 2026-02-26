@extends('layouts.app')
@section('title', 'Create Department')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-plus-lg me-1"></i> Create Department</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.departments.store') }}">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g., JHS" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., Junior High School" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Optional description">{{ old('description') }}</textarea>
            </div>
            <div class="alert alert-info small mb-3">
                <i class="bi bi-info-circle me-1"></i>
                To assign a <strong>Department Head</strong>, first create faculty accounts in
                <strong>Faculty Management</strong>, then edit this department.
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Department</button>
                <a href="{{ route('registrar.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
