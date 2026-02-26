@extends('layouts.app')
@section('title', 'Edit Student')

@section('content')
<div class="card" style="max-width: 700px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-pencil me-1"></i> Edit Student: {{ $student->user->name }}</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.students.update', $student) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $student->user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $student->user->email) }}" required>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Student ID</label>
                    <input type="text" name="student_id" class="form-control" value="{{ old('student_id', $student->student_id) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Program</label>
                    <select name="program_id" class="form-select" required>
                        @foreach($programs as $p)
                            <option value="{{ $p->id }}" {{ old('program_id', $student->program_id) == $p->id ? 'selected' : '' }}>{{ $p->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Year</label>
                    <select name="year_level" class="form-select">
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}" {{ old('year_level', $student->year_level) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['applicant','active','inactive','graduated','dropped'] as $s)
                            <option value="{{ $s }}" {{ old('status', $student->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select</option>
                        @foreach(['male','female','other'] as $g)
                            <option value="{{ $g }}" {{ old('gender', $student->gender) == $g ? 'selected' : '' }}>{{ ucfirst($g) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Contact</label>
                    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $student->contact_number) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address', $student->address) }}</textarea>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Guardian Name</label>
                    <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $student->guardian_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Guardian Contact</label>
                    <input type="text" name="guardian_contact" class="form-control" value="{{ old('guardian_contact', $student->guardian_contact) }}">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update</button>
                <a href="{{ route('registrar.students.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
