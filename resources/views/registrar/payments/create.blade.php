@extends('layouts.app')
@section('title', 'Record Payment')

@section('content')
@php
$selectedEnrollmentId = old('enrollment_id', $enrollment?->id);
$selectedEnrollment = $enrollment ?? $enrollments->firstWhere('id', (int) $selectedEnrollmentId);
$paymentTotal = $breakdown['total'] ?? ($selectedEnrollment->payment_total ?? ($selectedEnrollment ? (float) $selectedEnrollment->total_amount : 0));
$paymentBalance = $breakdown['balance'] ?? ($selectedEnrollment->payment_balance ?? ($selectedEnrollment ? $selectedEnrollment->balance() : 0));
@endphp

<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route($routeBase . 'payments.create') }}" class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fw-semibold">Enrollment</label>
                        <select name="enrollment_id" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Enrollment</option>
                            @foreach($enrollments as $enr)
                            <option value="{{ $enr->id }}" {{ (string) $selectedEnrollmentId === (string) $enr->id ? 'selected' : '' }}>
                                {{ $enr->student->user->name ?? 'N/A' }}
                                - {{ $enr->semester->academicYear->name ?? '' }} {{ $enr->semester->name ?? '' }}
                                - Due &#8369;{{ number_format($enr->payment_balance ?? $enr->balance(), 2) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @if($selectedEnrollment)
                    <div class="col-sm-6 col-lg-2">
                        <div class="text-muted small">Student</div>
                        <div class="fw-semibold">{{ $selectedEnrollment->student->user->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <div class="text-muted small">Student ID</div>
                        <div class="fw-semibold">{{ $selectedEnrollment->student->student_id ?? 'No Student ID' }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-1">
                        <div class="text-muted small">Status</div>
                        @php $statusColor = ['pending' => 'warning', 'assessed' => 'info', 'enrolled' => 'success']; @endphp
                        <span class="badge bg-{{ $statusColor[$selectedEnrollment->status] ?? 'secondary' }}">{{ ucfirst($selectedEnrollment->status) }}</span>
                    </div>
                    <div class="col-sm-6 col-lg-2 text-lg-end">
                        <div class="text-muted small">Amount Due</div>
                        <div class="fs-5 fw-bold text-primary">&#8369;{{ number_format($paymentBalance, 2) }}</div>
                    </div>
                    @endif
                    <noscript>
                        <div class="col-12">
                            <button type="submit" class="btn btn-sm btn-outline-primary">Load Enrollment</button>
                        </div>
                    </noscript>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        @if($selectedEnrollment && $pageError)
        <div class="alert alert-warning">
            <div class="fw-semibold">Assessment unavailable</div>
            <div class="small">{{ $pageError }}</div>
        </div>
        @elseif($selectedEnrollment && !empty($breakdown))
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-calculator me-1"></i> Payment Assessment</h6>
                <span class="badge bg-{{ $breakdown['pricing_type'] === 'flat' ? 'info text-dark' : 'primary' }}">
                    {{ $breakdown['pricing_type'] === 'flat' ? 'Flat Rate' : 'Per Unit' }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        @if($breakdown['pricing_type'] === 'flat')
                        <tbody>
                            <tr>
                                <td class="text-muted">Flat Tuition</td>
                                <td class="text-end fw-semibold">&#8369;{{ number_format($breakdown['flat_amount'], 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td class="text-muted">Miscellaneous Fee</td>
                                <td class="text-end">&#8369;{{ number_format($breakdown['misc_fee'], 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td class="text-muted">Registration Fee</td>
                                <td class="text-end">&#8369;{{ number_format($breakdown['reg_fee'], 2) }}</td>
                            </tr>
                        </tbody>
                        @else
                        @php
                        $lecRate = $breakdown['structure']->lecture_rate ?? 0;
                        $labRate = $breakdown['structure']->lab_rate ?? 0;
                        @endphp
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th class="text-end">Lec</th>
                                <th class="text-end">Lab</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($breakdown['items'] as $item)
                            <tr>
                                <td>
                                    {{ $item['subject_code'] }}
                                    <small class="text-muted d-block">{{ $item['subject_name'] }}</small>
                                </td>
                                <td class="text-end">
                                    {{ $item['lecture_units'] }} &#215; &#8369;{{ number_format($lecRate, 2) }}<br>
                                    <span class="text-primary fw-semibold">&#8369;{{ number_format($item['lecture_cost'], 2) }}</span>
                                </td>
                                <td class="text-end">
                                    {{ $item['lab_units'] }} &#215; &#8369;{{ number_format($labRate, 2) }}<br>
                                    <span class="text-primary fw-semibold">&#8369;{{ number_format($item['lab_cost'], 2) }}</span>
                                </td>
                                <td class="text-end fw-bold">&#8369;{{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-semibold">Miscellaneous Fee</td>
                                <td>&#8369;{{ number_format($breakdown['misc_fee'], 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-semibold">Registration Fee</td>
                                <td>&#8369;{{ number_format($breakdown['reg_fee'], 2) }}</td>
                            </tr>
                        </tbody>
                        @endif
                        <tfoot>
                            <tr class="table-primary">
                                <td colspan="{{ $breakdown['pricing_type'] === 'flat' ? '1' : '3' }}" class="text-end fw-bold">Total Payable</td>
                                <td class="fw-bold">&#8369;{{ number_format($breakdown['total'], 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="{{ $breakdown['pricing_type'] === 'flat' ? '1' : '3' }}" class="text-end fw-semibold">Verified Payments</td>
                                <td>&#8369;{{ number_format($breakdown['total_paid'], 2) }}</td>
                            </tr>
                            <tr class="{{ $breakdown['balance'] > 0 ? 'table-danger' : 'table-success' }}">
                                <td colspan="{{ $breakdown['pricing_type'] === 'flat' ? '1' : '3' }}" class="text-end fw-bold">Balance</td>
                                <td class="fw-bold">&#8369;{{ number_format($breakdown['balance'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @elseif($selectedEnrollment)
        <div class="alert alert-warning">
            No tuition structure is available for this enrollment yet, so the amount due cannot be computed.
        </div>
        @endif

        @if($selectedEnrollment && !empty($breakdown))
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-md-4">
                        <div class="text-muted small">Total Payable</div>
                        <div class="fs-4 fw-bold text-primary">&#8369;{{ number_format($breakdown['total'], 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Verified Payments</div>
                        <div class="fs-4 fw-bold text-success">&#8369;{{ number_format($breakdown['total_paid'], 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Remaining Balance</div>
                        <div class="fs-4 fw-bold {{ $breakdown['balance'] > 0 ? 'text-danger' : 'text-success' }}">&#8369;{{ number_format($breakdown['balance'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-plus-lg me-1"></i> Record Payment</h6>
            </div>
            <div class="card-body">
                @if(!$selectedEnrollment)
                <div class="text-muted">Select an enrollment first to start the cashier process.</div>
                @elseif($pageError || empty($breakdown))
                <div class="text-muted">Payment entry is unavailable until the system can compute the student balance.</div>
                @else
                <form method="POST" action="{{ route($routeBase . 'payments.store') }}">
                    @csrf
                    <input type="hidden" name="enrollment_id" value="{{ $selectedEnrollment->id }}">

                    <div class="alert alert-light border mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Amount to collect</span>
                            <span class="fw-bold text-primary">&#8369;{{ number_format($paymentBalance, 2) }}</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Amount</label>
                            <input type="number" name="amount" class="form-control" value="{{ old('amount', $paymentBalance > 0 ? number_format($paymentBalance, 2, '.', '') : '') }}" step="0.01" min="0.01" max="{{ max($paymentBalance, 0.01) }}" required>
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
                        <a href="{{ route($routeBase . 'payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection