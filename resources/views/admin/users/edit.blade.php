@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-pencil me-1"></i> Edit User: {{ $user->name }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Role</label>
                <select name="role" class="form-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}"
                            {{ $user->roles->contains('slug', $role->slug) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="form-check-input" name="is_active" value="1"
                       id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
