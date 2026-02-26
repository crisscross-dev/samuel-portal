@extends('layouts.app')
@section('title', 'Payment Details')

@section('content')
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0"><i class="bi bi-receipt me-1"></i> Assessment & Payments — {{ $enrollment->semester->academicYear->name ?? '' }}, {{ $enrollment->semester->name ?? '' }}</h5></div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Assessment Breakdown</h6>
                @if($breakdown)
                    <table class="table table-sm">
                        <tbody>
                            @foreach($breakdown['items'] as $item)
                                <tr>
                                    <td>{{ $item['description'] }}</td>
                                    <td class="text-end">₱{{ number_format($item['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-end">₱{{ number_format($breakdown['total'], 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-muted">Assessment details unavailable.</p>
                @endif
            </div>
            <div class="col-md-6">
                <h6 class="border-bottom pb-2 mb-3">Summary</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total Amount</span>
                        <strong>₱{{ number_format($enrollment->total_amount, 2) }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total Paid</span>
                        <strong class="text-success">₱{{ number_format($enrollment->totalPaid(), 2) }}</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Balance</span>
                        <strong class="text-{{ $enrollment->balance() > 0 ? 'danger' : 'success' }}">₱{{ number_format($enrollment->balance(), 2) }}</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-credit-card me-1"></i> Payment History</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollment->payments as $i => $pay)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') }}</td>
                            <td>₱{{ number_format($pay->amount, 2) }}</td>
                            <td>{{ ucfirst($pay->payment_method) }}</td>
                            <td>
                                <span class="badge bg-{{ $pay->status === 'verified' ? 'success' : ($pay->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($pay->status) }}
                                </span>
                            </td>
                            <td>{{ $pay->reference_number ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No payments recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('student.enrollment') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
@endsection
