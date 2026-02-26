@extends('layouts.app')
@section('title', 'Application Details')

@section('content')
<div class="mb-3">
    <a href="{{ route('registrar.applications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Applications
    </a>
</div>

<div class="row g-4">
    {{-- Main Details --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-file-earmark-person me-1"></i> Application #{{ $application->id }}</h6>
                @php
                    $badge = match($application->status) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'warning',
                    };
                @endphp
                <span class="badge bg-{{ $badge }} fs-6">{{ ucfirst($application->status) }}</span>
            </div>
            <div class="card-body">
                {{-- Personal Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-person me-1"></i> Personal Information</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted" style="width:45%">First Name</td><td class="fw-semibold">{{ $application->first_name }}</td></tr>
                            <tr><td class="text-muted">Last Name</td><td class="fw-semibold">{{ $application->last_name }}</td></tr>
                            <tr><td class="text-muted">Email</td><td>{{ $application->email }}</td></tr>
                            <tr><td class="text-muted">Contact</td><td>{{ $application->contact_number ?? '—' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted" style="width:45%">Date of Birth</td><td>{{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') : '—' }}</td></tr>
                            <tr><td class="text-muted">Gender</td><td>{{ $application->gender ? ucfirst($application->gender) : '—' }}</td></tr>
                            <tr><td class="text-muted">Address</td><td>{{ $application->address ?? '—' }}</td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                {{-- Academic Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-book me-1"></i> Academic Information</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted" style="width:45%">Program</td><td class="fw-semibold">{{ $application->program->name ?? 'N/A' }}</td></tr>
                            <tr><td class="text-muted">Program Code</td><td>{{ $application->program->code ?? 'N/A' }}</td></tr>
                            <tr><td class="text-muted">Year Level</td><td>{{ $application->year_level }}</td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                {{-- Guardian Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="bi bi-shield-check me-1"></i> Guardian / Parent</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td class="text-muted" style="width:45%">Guardian Name</td><td>{{ $application->guardian_name ?? '—' }}</td></tr>
                            <tr><td class="text-muted">Guardian Contact</td><td>{{ $application->guardian_contact ?? '—' }}</td></tr>
                        </table>
                    </div>
                </div>

                {{-- Document --}}
                @if($application->document_path)
                    <hr>
                    <h6 class="text-primary fw-bold mb-3"><i class="bi bi-paperclip me-1"></i> Uploaded Document</h6>
                    <a href="{{ asset('storage/' . $application->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i> View / Download Document
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar Actions --}}
    <div class="col-lg-4">
        {{-- Status & Timeline --}}
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-clock-history me-1"></i> Timeline</h6></div>
            <div class="card-body">
                <div class="d-flex align-items-start gap-2 mb-3">
                    <i class="bi bi-circle-fill text-primary" style="font-size:0.5rem; margin-top:6px;"></i>
                    <div>
                        <div class="small fw-semibold">Application Submitted</div>
                        <div class="text-muted small">{{ $application->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @if($application->reviewed_at)
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-circle-fill text-{{ $application->isApproved() ? 'success' : 'danger' }}" style="font-size:0.5rem; margin-top:6px;"></i>
                        <div>
                            <div class="small fw-semibold">{{ $application->isApproved() ? 'Approved' : 'Rejected' }}</div>
                            <div class="text-muted small">{{ $application->reviewed_at->format('M d, Y h:i A') }}</div>
                            @if($application->reviewer)
                                <div class="text-muted small">By: {{ $application->reviewer->name }}</div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-circle text-warning" style="font-size:0.5rem; margin-top:6px;"></i>
                        <div>
                            <div class="small fw-semibold text-warning">Awaiting Review</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Remarks --}}
        @if($application->remarks)
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-chat-left-text me-1"></i> Remarks</h6></div>
                <div class="card-body">
                    <p class="mb-0">{{ $application->remarks }}</p>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        @if($application->isPending())
            <div class="card">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-gear me-1"></i> Actions</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-success w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="bi bi-check-circle me-1"></i> Approve Application
                        </button>
                    </div>
                    <button type="button" class="btn btn-outline-danger w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle me-1"></i> Reject Application
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Approve Modal --}}
@if($application->isPending())
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('registrar.applications.approve', $application) }}">
                    @csrf @method('PATCH')
                    <div class="modal-header bg-success text-white">
                        <h6 class="modal-title"><i class="bi bi-check-circle me-1"></i> Approve Application</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to approve <strong>{{ $application->fullName() }}</strong>'s application.</p>
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-info-circle me-1"></i> This will:
                            <ul class="mb-0 mt-1">
                                <li>Create a user account for the applicant</li>
                                <li>Generate a Student ID</li>
                                <li>Create a student profile with <strong>admitted</strong> status</li>
                                <li>Set default password to <code>password</code></li>
                            </ul>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Remarks (optional)</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="Any notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Approve & Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('registrar.applications.reject', $application) }}">
                    @csrf @method('PATCH')
                    <div class="modal-header bg-danger text-white">
                        <h6 class="modal-title"><i class="bi bi-x-circle me-1"></i> Reject Application</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Reject <strong>{{ $application->fullName() }}</strong>'s application?</p>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Reason..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i> Reject Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
