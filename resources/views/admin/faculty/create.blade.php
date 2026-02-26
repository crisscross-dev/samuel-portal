@extends('layouts.app')
@section('title', 'Create Faculty Account')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-person-plus me-1"></i> Create Faculty Account</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.faculty.store') }}">
            @csrf

            {{-- User Information --}}
            <h6 class="text-muted border-bottom pb-2 mb-3">
                <i class="bi bi-person me-1"></i> User Information
            </h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="e.g., Prof. Juan Dela Cruz" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="e.g., juan@scc.edu.ph" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Min. 8 characters (leave blank to auto-generate)">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">If left blank, a random password will be generated.</small>
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
                        value="{{ old('employee_id', $nextEmployeeId) }}" required>
                    @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                        <option value="">— Select Department —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->code }} — {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Specialization</label>
                    <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror"
                        value="{{ old('specialization') }}" placeholder="e.g., Mathematics">
                    @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                    {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active Account</label>
            </div>

            <div class="alert alert-info small">
                <i class="bi bi-info-circle me-1"></i>
                This will create a <strong>User account</strong> with the <strong>Faculty</strong> role and link it to the selected department.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Create Faculty Account
                </button>
                <a href="{{ route('admin.faculty.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
