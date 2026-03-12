@extends('layouts.app')
@section('title', 'Edit Subject')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-pencil me-1"></i> Edit Subject: {{ $subject->code }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.subjects.update', $subject) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $subject->code) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $subject->name) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Optional">{{ old('description', $subject->description) }}</textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Lecture Units</label>
                    <input type="number" name="lecture_units" class="form-control" value="{{ old('lecture_units', $subject->lecture_units) }}" min="0" max="10" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Lab Units</label>
                    <input type="number" name="lab_units" class="form-control" value="{{ old('lab_units', $subject->lab_units) }}" min="0" max="10" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update Subject</button>
                <a href="{{ route('registrar.subjects.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection