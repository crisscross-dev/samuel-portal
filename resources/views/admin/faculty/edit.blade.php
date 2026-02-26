@extends('layouts.app')
@section('title', 'Edit Faculty Account')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-pencil me-1"></i> Edit Faculty: {{ $faculty->user->name }}</h6>
        <span class="badge bg-{{ $faculty->is_active ? 'success' : 'danger' }}">
            {{ $faculty->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.faculty.update', $faculty) }}">
            @csrf @method('PUT')

            {{-- User Information --}}
            <h6 class="text-muted border-bottom pb-2 mb-3">
                <i class="bi bi-person me-1"></i> User Information
            </h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $faculty->user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $faculty->user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Leave blank to keep current">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Only fill in if you want to change the password.</small>
                </div>
            </div>

            {{-- Faculty Information --}}
            <h6 class="text-muted border-bottom pb-2 mb-3">
                <i class="bi bi-briefcase me-1"></i> Faculty Information
            </h6>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror"
                        value="{{ old('employee_id', $faculty->employee_id) }}" required>
                    @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                        <option value="">— Select Department —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $faculty->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->code }} — {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Specialization</label>
                    <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror"
                        value="{{ old('specialization', $faculty->specialization) }}">
                    @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                    {{ old('is_active', $faculty->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active Account</label>
            </div>

            @if($faculty->headedDepartment)
                <div class="alert alert-warning small">
                    <i class="bi bi-star-fill me-1"></i>
                    This faculty is currently the <strong>Department Head</strong> of
                    <strong>{{ $faculty->headedDepartment->name }}</strong>.
                    Deactivating or changing their department will remove this assignment.
                </div>
            @endif

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Update Faculty
                </button>
                <a href="{{ route('admin.faculty.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
