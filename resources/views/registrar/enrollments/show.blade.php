@extends('layouts.app')
@section('title', 'Enrollment Details')

@section('content')
<div class="row g-4">
    {{-- Student & Enrollment Info --}}
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Enrollment Info</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Student</th>
                        <td>{{ $enrollment->student->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Student ID</th>
                        <td>{{ $enrollment->student->student_id ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Program</th>
                        <td>{{ $enrollment->student->program->code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Section</th>
                        <td>{{ $enrollment->student->section?->displayName() ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Semester</th>
                        <td>{{ $enrollment->semester->academicYear->name ?? '' }} - {{ $enrollment->semester->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status</th>
                        <td>
                            @php $c = ['pending'=>'warning','assessed'=>'info','enrolled'=>'success','dropped'=>'danger']; @endphp
                            <span class="badge bg-{{ $c[$enrollment->status] ?? 'secondary' }}">{{ ucfirst($enrollment->status) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Units</th>
                        <td>{{ $enrollment->total_units }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Total Amount</th>
                        <td>&#8369;{{ number_format($enrollment->total_amount, 2) }}</td>
                    </tr>
                    @if($enrollment->enrolled_at)
                    <tr>
                        <th class="text-muted">Enrolled At</th>
                        <td>{{ $enrollment->enrolled_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @endif
                </table>

                <div class="d-flex gap-2 mt-3">
                    @if($enrollment->status === 'assessed' && $enrollment->isFullyPaid())
                    <form action="{{ route('registrar.enrollments.finalize', $enrollment) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm" onclick="return confirm('Finalize this enrollment?')">
                            <i class="bi bi-check-circle me-1"></i> Finalize Enrollment
                        </button>
                    </form>
                    @elseif(in_array($enrollment->status, ['pending', 'assessed', 'enrolled']))
                    <span class="badge bg-dark-subtle text-dark-emphasis border">Payment handling is assigned to Cashier.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Subjects --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Enrolled Subjects</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            @if(!empty($breakdown) && $breakdown['pricing_type'] === 'per_unit')
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Section</th>
                                <th class="text-center">Lec</th>
                                <th class="text-center">Lab</th>
                                <th class="text-center">Total</th>
                            </tr>
                            @else
                            <tr>
                                <th>Code</th>
                                <th>Subject</th>
                                <th>Section</th>
                                <th class="text-center">Units</th>
                            </tr>
                            @endif
                        </thead>
                        <tbody>
                            @foreach($enrollment->enrollmentSubjects as $es)
                            @if(!empty($breakdown) && $breakdown['pricing_type'] === 'per_unit')
                            <tr>
                                <td>{{ $es->subject->code }}</td>
                                <td>{{ $es->subject->name }}</td>
                                <td>{{ $es->section->name ?? '' }}</td>
                                <td class="text-center">{{ $es->subject->lecture_units }}</td>
                                <td class="text-center">{{ $es->subject->lab_units }}</td>
                                <td class="text-center fw-semibold">{{ $es->subject->totalUnits() }}</td>
                            </tr>
                            @else
                            <tr>
                                <td>{{ $es->subject->code }}</td>
                                <td>{{ $es->subject->name }}</td>
                                <td>{{ $es->section->name ?? '' }}</td>
                                <td class="text-center">{{ $es->subject->totalUnits() }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                        @if(!empty($breakdown) && $breakdown['pricing_type'] === 'per_unit')
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end text-muted fw-semibold">Totals</td>
                                <td class="text-center fw-semibold">{{ $enrollment->enrollmentSubjects->sum(fn($e) => $e->subject->lecture_units) }}</td>
                                <td class="text-center fw-semibold">{{ $enrollment->enrollmentSubjects->sum(fn($e) => $e->subject->lab_units) }}</td>
                                <td class="text-center fw-bold">{{ $enrollment->total_units }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Assessment & Payments --}}
    <div class="col-md-7">
        @if($breakdown)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-calculator me-1"></i> Assessment Breakdown</h6>
                @if(!empty($breakdown['structure']))
                <span class="badge bg-{{ $breakdown['pricing_type'] === 'flat' ? 'info text-dark' : 'primary' }}">
                    {{ $breakdown['pricing_type'] === 'flat' ? 'Flat Rate' : 'Per Unit' }}
                </span>
                @endif
            </div>

            {{-- Pricing Structure Info Panel --}}
            @if(!empty($breakdown['structure']))
            @php $s = $breakdown['structure']; @endphp
            <div class="px-3 pt-3 pb-2 border-bottom bg-light">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <span class="fw-semibold text-dark">
                        {{ $s->label ?: ($s->department->name . ' &mdash; ' . $s->academicYear->name) }}
                    </span>
                    <a href="{{ route('registrar.pricing.show', $s) }}" class="badge bg-light text-primary border border-primary text-decoration-none small" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i>View Structure
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-3 small text-muted">
                    <span><i class="bi bi-building me-1"></i>{{ $s->department->name ?? '—' }}</span>
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $s->academicYear->name ?? '—' }}</span>
                    @if($breakdown['pricing_type'] === 'per_unit')
                    <span class="text-primary fw-semibold"><i class="bi bi-pencil-square me-1"></i>Lec Rate: &#8369;{{ number_format($s->lecture_rate, 2) }}</span>
                    <span class="text-primary fw-semibold"><i class="bi bi-flask me-1"></i>Lab Rate: &#8369;{{ number_format($s->lab_rate, 2) }}</span>
                    @else
                    <span class="text-info fw-semibold"><i class="bi bi-cash me-1"></i>Flat: &#8369;{{ number_format($s->flat_amount, 2) }}</span>
                    @endif
                    <span><i class="bi bi-receipt me-1"></i>Misc: &#8369;{{ number_format($s->misc_fee, 2) }}</span>
                    <span><i class="bi bi-clipboard me-1"></i>Reg: &#8369;{{ number_format($s->reg_fee, 2) }}</span>
                </div>
            </div>
            @endif

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
                                <th class="text-end">Lec (units &#215; &#8369;{{ number_format($lecRate, 2) }})</th>
                                <th class="text-end">Lab (units &#215; &#8369;{{ number_format($labRate, 2) }})</th>
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
                                <td colspan="{{ $breakdown['pricing_type'] === 'flat' ? '1' : '3' }}" class="text-end fw-bold">Total</td>
                                <td class="fw-bold">&#8369;{{ number_format($breakdown['total'], 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="{{ $breakdown['pricing_type'] === 'flat' ? '1' : '3' }}" class="text-end fw-semibold">Total Paid</td>
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
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-credit-card me-1"></i> Payments</h6>
                <span class="text-muted small">Managed by Cashier</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Ref#</th>
                                <th>Status</th>
                                <th>Verified By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollment->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>&#8369;{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                <td>{{ $payment->reference_number ?? '-' }}</td>
                                <td>
                                    @php $pc = ['pending'=>'warning','verified'=>'success','rejected'=>'danger']; @endphp
                                    <span class="badge bg-{{ $pc[$payment->status] ?? 'secondary' }}">{{ ucfirst($payment->status) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $payment->verifier?->name ?? 'Cashier review pending' }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No payments recorded.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection