@extends('layouts.app')
@section('title', 'Create Department')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-building me-1"></i> Create Department</h6>
    </div>
    <div class="card-body">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Note:</strong> To assign a Department Head, first create faculty accounts in Faculty Management, then edit this department.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <form method="POST" action="{{ route('admin.departments.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Department Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                    value="{{ old('code') }}" required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">e.g., JHS, SHS, CCS</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                    rows="3">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active Department</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create</button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
