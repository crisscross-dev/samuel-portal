@extends('layouts.app')
@section('title', 'Payment Logs')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-clock-history me-1"></i> Payment Logs</h6>
        <div class="d-flex gap-2">
            <a href="{{ route($routeBase . 'payments.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route($routeBase . 'payments.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Open Payment
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['pending','verified','rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Ref#</th>
                        <th>Status</th>
                        <th>Verified By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $payment->enrollment->student->user->name ?? 'N/A' }}</div>
                            <div class="text-muted small">{{ $payment->enrollment->student->student_id ?? 'No Student ID' }}</div>
                        </td>
                        <td>&#8369;{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td>{{ $payment->reference_number ?? '-' }}</td>
                        <td>
                            @php $pc = ['pending'=>'warning','verified'=>'success','rejected'=>'danger']; @endphp
                            <span class="badge bg-{{ $pc[$payment->status] ?? 'secondary' }}">{{ ucfirst($payment->status) }}</span>
                        </td>
                        <td>
                            @if($payment->verifier)
                            <div class="small fw-semibold">{{ $payment->verifier->name }}</div>
                            <div class="text-muted small">{{ $payment->verified_at?->format('M d, Y h:i A') }}</div>
                            @else
                            <span class="text-muted small">Pending review</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->status === 'pending')
                            <form action="{{ route($routeBase . 'payments.verify', $payment) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-success" title="Verify"><i class="bi bi-check-lg"></i></button>
                            </form>
                            <form action="{{ route($routeBase . 'payments.reject', $payment) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                            </form>
                            @else
                            <span class="text-muted small">Logged</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">No payment logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $payments->links() }}
    </div>
</div>
@endsection