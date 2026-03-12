@extends('layouts.app')
@section('title', 'Create Subject')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-plus-lg me-1"></i> Create Subject</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.subjects.store') }}">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g., IT101" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., Programming Fundamentals" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional">{{ old('description') }}</textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Lecture Units</label>
                    <input type="number" name="lecture_units" class="form-control" value="{{ old('lecture_units', 3) }}" min="0" max="10" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Lab Units</label>
                    <input type="number" name="lab_units" class="form-control" value="{{ old('lab_units', 0) }}" min="0" max="10" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Subject</button>
                <a href="{{ route('registrar.subjects.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection