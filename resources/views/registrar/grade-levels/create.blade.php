@extends('layouts.app')
@section('title', 'Create Grade Level')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-plus-lg me-1"></i> Create Grade Level</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.grade-levels.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Department</label>
                <select name="department_id" class="form-select" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->code }} — {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g., Grade 7" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Level Order</label>
                    <input type="number" name="level_order" class="form-control" value="{{ old('level_order', 1) }}" min="1" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Grade Level</button>
                <a href="{{ route('registrar.grade-levels.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
