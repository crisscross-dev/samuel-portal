@extends('layouts.app')
@section('title', 'Create User')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-person-plus me-1"></i> Create New User</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Role</label>
                <select name="role" class="form-select" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ old('role') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
