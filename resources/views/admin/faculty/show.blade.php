@extends('layouts.app')
@section('title', 'Faculty Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-person-badge me-1"></i> Faculty Profile</h5>
    <div class="d-flex gap-2">
        @can('update', $faculty)
            <a href="{{ route('admin.faculty.edit', $faculty) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        @endcan
        @can('toggleActive', $faculty)
            <form method="POST" action="{{ route('admin.faculty.toggle-active', $faculty) }}"
                  onsubmit="return confirm('{{ $faculty->is_active ? 'Deactivate' : 'Activate' }} this faculty?')">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-outline-{{ $faculty->is_active ? 'warning' : 'success' }}">
                    <i class="bi bi-{{ $faculty->is_active ? 'pause-circle' : 'play-circle' }} me-1"></i>
                    {{ $faculty->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        @endcan
        <a href="{{ route('admin.faculty.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- Personal & Account Information --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-person me-1"></i> Personal Information</h6></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:40%">Employee ID</th>
                        <td><code>{{ $faculty->employee_id }}</code></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Full Name</th>
                        <td>{{ $faculty->user->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td>{{ $faculty->user->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Department</th>
                        <td>
                            @if($faculty->department)
                                <span class="badge bg-info text-dark">{{ $faculty->department->code }}</span>
                                {{ $faculty->department->name }}
                            @else
                                <span class="text-muted">— Unassigned —</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Specialization</th>
                        <td>{{ $faculty->specialization ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status</th>
                        <td>
                            <span class="badge bg-{{ $faculty->is_active ? 'success' : 'danger' }}">
                                {{ $faculty->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Dept Head</th>
                        <td>
                            @if($faculty->headedDepartment)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-star-fill me-1"></i>{{ $faculty->headedDepartment->name }}
                                </span>
                            @else
                                <span class="text-muted">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Account Created</th>
                        <td>{{ $faculty->created_at->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Teaching Loads --}}
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-journal-bookmark me-1"></i> Teaching Assignments</h6>
                <span class="badge bg-primary">{{ $faculty->sectionSubjects->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($faculty->sectionSubjects->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Section</th>
                                    <th>Grade Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faculty->sectionSubjects as $ss)
                                    <tr>
                                        <td>{{ $ss->subject->name ?? '—' }}</td>
                                        <td>{{ $ss->section->name ?? '—' }}</td>
                                        <td>
                                            @if($ss->section && $ss->section->gradeLevel)
                                                {{ $ss->section->gradeLevel->name }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        No teaching assignments yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
