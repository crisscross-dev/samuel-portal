@extends('layouts.app')
@section('title', 'Edit Grade Level')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-pencil me-1"></i> Edit Grade Level: {{ $gradeLevel->name }}</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.grade-levels.update', $gradeLevel) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Department</label>
                <select name="department_id" class="form-select" required>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $gradeLevel->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->code }} — {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $gradeLevel->name) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Level Order</label>
                    <input type="number" name="level_order" class="form-control" value="{{ old('level_order', $gradeLevel->level_order) }}" min="1" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $gradeLevel->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
                <a href="{{ route('registrar.grade-levels.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
