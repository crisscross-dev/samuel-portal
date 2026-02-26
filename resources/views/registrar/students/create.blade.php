@extends('layouts.app')
@section('title', 'Add Student')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-person-plus me-1"></i> Add New Student</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.students.store') }}">
            @csrf
            <h6 class="text-muted mb-3">Account Information</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <hr>
            <h6 class="text-muted mb-3">Student Information</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Student ID</label>
                    <input type="text" name="student_id" class="form-control" value="{{ old('student_id') }}" placeholder="2025-00001" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Program</label>
                    <select name="program_id" class="form-select" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $p)
                            <option value="{{ $p->id }}" {{ old('program_id') == $p->id ? 'selected' : '' }}>{{ $p->code }} - {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Year Level</label>
                    <select name="year_level" class="form-select" required>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('year_level', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Guardian Name</label>
                    <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Guardian Contact</label>
                    <input type="text" name="guardian_contact" class="form-control" value="{{ old('guardian_contact') }}">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Student</button>
                <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
