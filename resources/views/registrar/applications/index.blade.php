@extends('layouts.app')
@section('title', 'Applications')

@section('content')
{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-stack"></i></div>
                <div>
                    <div class="text-muted small">Total</div>
                    <div class="fw-bold fs-5">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="text-muted small">Pending</div>
                    <div class="fw-bold fs-5">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="text-muted small">Approved</div>
                    <div class="fw-bold fs-5">{{ $stats['approved'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="text-muted small">Rejected</div>
                    <div class="fw-bold fs-5">{{ $stats['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('registrar.applications.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Program</label>
                <select name="program_id" class="form-select form-select-sm">
                    <option value="">All Programs</option>
                    @foreach($programs as $p)
                    <option value="{{ $p->id }}" {{ request('program_id') == $p->id ? 'selected' : '' }}>{{ $p->code }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Search</label>
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- Applications Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-file-earmark-person me-1"></i> Admission Applications</h6>
        <span class="badge bg-secondary">{{ $applications->total() }} total</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>App ID</th>
                        <th>Program</th>
                        <th>Exam Schedule</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date Applied</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $app->fullName() }}</div>
                            <div class="text-muted small">{{ $app->email }}</div>
                        </td>
                        <td>
                            @if($app->app_id)
                            <code class="small">{{ $app->app_id }}</code>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $app->program->code ?? 'N/A' }}</span></td>
                        <td>
                            @if($app->exam_schedule === 'saturday_9am')
                            <span class="badge" style="background:#fef9c3; color:#92400e; border:1px solid #fde68a;">Sat 9:00 AM</span>
                            @elseif($app->exam_schedule === 'saturday_1pm')
                            <span class="badge" style="background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe;">Sat 1:00 PM</span>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @php $pay = $app->admissionPayment; @endphp
                            @if(!$pay)
                            <span class="badge bg-secondary">No payment</span>
                            @elseif($pay->payment_status === 'paid')
                            <span class="badge bg-success">Verified</span>
                            @else
                            <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @php
                            $badge = match($app->status) {
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'warning',
                            };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($app->status) }}</span>
                        </td>
                        <td>{{ $app->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('registrar.applications.show', $app) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View
                            </a>
                            @if($app->isPending())
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $app->id }}">
                                <i class="bi bi-check-lg"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $app->id }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            @endif
                        </td>
                    </tr>

                    {{-- Approve Modal --}}
                    @if($app->isPending())
                    <div class="modal fade" id="approveModal{{ $app->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('registrar.applications.approve', $app) }}">
                                    @csrf @method('PATCH')
                                    <div class="modal-header bg-success text-white">
                                        <h6 class="modal-title"><i class="bi bi-check-circle me-1"></i> Approve Application</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Approve <strong>{{ $app->fullName() }}</strong>'s application?</p>
                                        <p class="text-muted small mb-1">This will create a student account and send a <strong>confirmation email</strong> to the student with their exam schedule and login credentials.</p>
                                        @if($app->exam_schedule)
                                        <div class="alert alert-info py-1 px-2 small mb-3">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Exam slot: <strong>{{ $app->exam_schedule === 'saturday_9am' ? 'Saturday – 9:00 AM' : 'Saturday – 1:00 PM' }}</strong>
                                        </div>
                                        @endif
                                        <div class="mb-0">
                                            <label class="form-label fw-semibold">Remarks (optional)</label>
                                            <textarea name="remarks" class="form-control" rows="2" placeholder="Any notes..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Approve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Reject Modal --}}
                    <div class="modal fade" id="rejectModal{{ $app->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('registrar.applications.reject', $app) }}">
                                    @csrf @method('PATCH')
                                    <div class="modal-header bg-danger text-white">
                                        <h6 class="modal-title"><i class="bi bi-x-circle me-1"></i> Reject Application</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Reject <strong>{{ $app->fullName() }}</strong>'s application?</p>
                                        <div class="mb-0">
                                            <label class="form-label fw-semibold">Reason for Rejection</label>
                                            <textarea name="remarks" class="form-control" rows="2" placeholder="Reason..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i> Reject</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No applications found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($applications->hasPages())
    <div class="card-footer">
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endsection