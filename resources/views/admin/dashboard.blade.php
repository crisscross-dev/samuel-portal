@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="text-muted small">Total Students</div>
                    <div class="fw-bold fs-5">{{ number_format($stats['total_students']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <div class="text-muted small">Faculty</div>
                    <div class="fw-bold fs-5">{{ number_format($stats['total_faculty']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-person-gear"></i></div>
                <div>
                    <div class="text-muted small">Total Users</div>
                    <div class="fw-bold fs-5">{{ number_format($stats['total_users']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-journal-check"></i></div>
                <div>
                    <div class="text-muted small">Active Enrollments</div>
                    <div class="fw-bold fs-5">{{ number_format($stats['active_enrollments']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="text-muted small">Pending Payments</div>
                    <div class="fw-bold fs-5">{{ number_format($stats['pending_payments']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="text-muted small">Total Revenue</div>
                    <div class="fw-bold fs-5">&#8369;{{ number_format($stats['total_revenue'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-people me-1"></i> Recent Users</h6>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Add User
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No users yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
