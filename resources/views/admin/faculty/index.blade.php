@extends('layouts.app')
@section('title', 'Faculty Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-person-workspace me-1"></i> Faculty Management</h6>
        <a href="{{ route('admin.faculty.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Faculty
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Specialization</th>
                        <th>Dept Head</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faculty as $fac)
                        <tr class="{{ !$fac->is_active ? 'table-secondary' : '' }}">
                            <td class="fw-semibold">{{ $fac->employee_id }}</td>
                            <td>{{ $fac->user->name }}</td>
                            <td><small class="text-muted">{{ $fac->user->email }}</small></td>
                            <td>
                                @if($fac->department)
                                    <span class="badge bg-primary">{{ $fac->department->code }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $fac->specialization ?? '—' }}</td>
                            <td>
                                @if($fac->headedDepartment)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill me-1"></i>{{ $fac->headedDepartment->code }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $fac->is_active ? 'success' : 'danger' }}">
                                    {{ $fac->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.faculty.show', $fac) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.faculty.edit', $fac) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.faculty.toggle-active', $fac) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $fac->is_active ? 'secondary' : 'success' }}"
                                            title="{{ $fac->is_active ? 'Deactivate' : 'Activate' }}"
                                            onclick="return confirm('{{ $fac->is_active ? 'Deactivate' : 'Reactivate' }} this faculty account?')">
                                            <i class="bi bi-{{ $fac->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.faculty.destroy', $fac) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete this faculty account? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-person-workspace fs-1 d-block mb-2"></i>
                                No faculty members found. <a href="{{ route('admin.faculty.create') }}">Create one now.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($faculty->hasPages())
        <div class="card-footer">
            {{ $faculty->links() }}
        </div>
    @endif
</div>
@endsection
