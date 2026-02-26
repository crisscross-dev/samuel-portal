@extends('layouts.app')
@section('title', 'Edit Department')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-pencil me-1"></i> Edit Department: {{ $department->name }}</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.departments.update', $department) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $department->code) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $department->name) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $department->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Department Head</label>
                <select name="head_faculty_id" class="form-select">
                    <option value="">— None —</option>
                    @foreach($facultyList as $fac)
                        <option value="{{ $fac->id }}" {{ old('head_faculty_id', $department->head_faculty_id) == $fac->id ? 'selected' : '' }}>
                            {{ $fac->user->name }} ({{ $fac->employee_id }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Only active faculty assigned to this department are shown.</small>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $department->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
                <a href="{{ route('registrar.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
