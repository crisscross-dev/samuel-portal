@extends('layouts.app')
@section('title', 'Payments')

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-cash-coin me-1"></i> Students Ready for Payment</h6>
        <div class="d-flex align-items-center gap-2">
            @if($routeBase === 'cashier.')
            <a href="{{ route($routeBase . 'payments.logs') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-clock-history me-1"></i> Payment Logs
            </a>
            @endif
            <span class="badge bg-primary">{{ $readyForPaymentEnrollments->count() }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($readyForPaymentEnrollments as $enrollment)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $enrollment->student->user->name ?? 'N/A' }}</div>
                            <div class="text-muted small">{{ $enrollment->student->student_id ?? 'No Student ID' }}</div>
                        </td>
                        <td>{{ $enrollment->semester->academicYear->name ?? '' }} - {{ $enrollment->semester->name ?? '' }}</td>
                        <td>
                            @php $statusColor = ['pending' => 'warning', 'assessed' => 'info', 'enrolled' => 'success']; @endphp
                            <span class="badge bg-{{ $statusColor[$enrollment->status] ?? 'secondary' }}">{{ ucfirst($enrollment->status) }}</span>
                        </td>
                        <td>
                            @if((float) ($enrollment->payment_total ?? 0) > 0)
                            <div class="fw-semibold">&#8369;{{ number_format($enrollment->payment_balance ?? $enrollment->balance(), 2) }}</div>
                            <div class="text-muted small">Payable amount ready</div>
                            @else
                            <div class="fw-semibold text-warning">No tuition structure</div>
                            <div class="text-muted small">Assessment cannot be computed yet</div>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route($routeBase . 'payments.create', ['enrollment_id' => $enrollment->id]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-credit-card me-1"></i>
                                Open Payment
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No students are currently waiting for payment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($showPaymentHistory)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-credit-card me-1"></i> Payments</h6>
        <div class="d-flex gap-2">
            <a href="{{ route($routeBase . 'payments.logs') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clock-history me-1"></i> Payment Logs</a>
            <a href="{{ route($routeBase . 'payments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Open Payment</a>
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
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Ref#</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->enrollment->student->user->name ?? 'N/A' }}</td>
                        <td>&#8369;{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        <td>{{ $payment->reference_number ?? '-' }}</td>
                        <td>
                            @php $pc = ['pending'=>'warning','verified'=>'success','rejected'=>'danger']; @endphp
                            <span class="badge bg-{{ $pc[$payment->status] ?? 'secondary' }}">{{ ucfirst($payment->status) }}</span>
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
                            <small class="text-muted">{{ $payment->verifier?->name ?? '' }}</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endif
@endsection