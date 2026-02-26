@extends('layouts.app')
@section('title', 'Record Payment')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-credit-card me-1"></i> Record Payment</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('registrar.payments.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Enrollment</label>
                <select name="enrollment_id" class="form-select" required>
                    <option value="">Select Enrollment</option>
                    @foreach($enrollments as $enr)
                        <option value="{{ $enr->id }}"
                            {{ old('enrollment_id', $enrollment?->id) == $enr->id ? 'selected' : '' }}>
                            {{ $enr->student->user->name ?? 'N/A' }} - &#8369;{{ number_format($enr->total_amount, 2) }}
                            (Balance: &#8369;{{ number_format($enr->balance(), 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Amount</label>
                    <input type="number" name="amount" class="form-control" value="{{ old('amount') }}" step="0.01" min="0.01" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Reference Number</label>
                    <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Remarks</label>
                <textarea name="remarks" class="form-control" rows="2">{{ old('remarks') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Record Payment</button>
                <a href="{{ route('registrar.payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
