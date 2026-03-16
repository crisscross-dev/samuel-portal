@extends('layouts.app')
@section('title', 'Student Accounts')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-person-check me-1"></i> Student Account Release Queue</h6>
        <span class="badge bg-primary">{{ $pendingAccounts->total() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Student Name</th>
                        <th>Application ID</th>
                        <th>Program</th>
                        <th>Payment Status</th>
                        <th>Account Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingAccounts as $row)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $row->student_name }}</div>
                            <div class="text-muted small">Student ID: {{ $row->student_id }}</div>
                        </td>
                        <td>{{ $row->app_id ?: 'N/A' }}</td>
                        <td>{{ $row->program_name }}</td>
                        <td>
                            <span class="badge bg-success">{{ ucfirst($row->computed_payment_status) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">{{ ucfirst($row->account_status) }}</span>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('registrar.student-accounts.release', $row->application_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Release this student portal account now?')">
                                    <i class="bi bi-send-check me-1"></i> Release Account
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No students are waiting for account release.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pendingAccounts->hasPages())
    <div class="card-footer bg-white">
        {{ $pendingAccounts->links() }}
    </div>
    @endif
</div>
@endsection