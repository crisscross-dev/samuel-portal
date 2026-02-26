@extends('layouts.app')
@section('title', 'Payments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-credit-card me-1"></i> Payments</h6>
        <a href="{{ route('registrar.payments.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Record Payment</a>
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
                    <tr><th>Student</th><th>Amount</th><th>Date</th><th>Method</th><th>Ref#</th><th>Status</th><th>Actions</th></tr>
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
                                    <form action="{{ route('registrar.payments.verify', $payment) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success" title="Verify"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('registrar.payments.reject', $payment) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                @else
                                    <small class="text-muted">{{ $payment->verifier?->name ?? '' }}</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">No payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endsection
