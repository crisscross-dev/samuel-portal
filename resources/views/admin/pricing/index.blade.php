@extends('layouts.app')
@section('title', 'Tuition Structures')

@section('content')
@php
$isRegistrarView = request()->routeIs('registrar.*');
$indexRoute = $isRegistrarView ? 'registrar.pricing.index' : 'admin.pricing.index';
$showRoute = $isRegistrarView ? 'registrar.pricing.show' : 'admin.pricing.show';
@endphp
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-tags me-1"></i> Tuition Structures</h6>
        @if(!$isRegistrarView)
        <a href="{{ route('admin.pricing.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i> New Structure</a>
        @endif
    </div>
    <div class="card-body border-bottom pb-3 mb-0">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->code }} — {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="academic_year_id" class="form-select form-select-sm">
                    <option value="">All Academic Years</option>
                    @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('academic_year_id') == $ay->id ? 'selected' : '' }}>
                        {{ $ay->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
            </div>
            @if(request()->hasAny(['department_id','academic_year_id']))
            <div class="col-md-2">
                <a href="{{ route($indexRoute) }}" class="btn btn-sm btn-outline-secondary w-100">Clear</a>
            </div>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Department</th>
                        <th>Academic Year</th>
                        <th>Type</th>
                        <th>Rate / Amount</th>
                        <th>Misc</th>
                        <th>Reg</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($structures as $ts)
                    <tr class="{{ !$ts->is_active ? 'table-secondary' : '' }}">
                        <td>
                            <span class="badge bg-primary">{{ $ts->department->code ?? '—' }}</span>
                            {{ $ts->department->name ?? '—' }}
                        </td>
                        <td>{{ $ts->academicYear->name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $ts->pricing_type === 'flat' ? 'info text-dark' : 'purple' }}">
                                {{ $ts->pricing_type === 'flat' ? 'Flat Rate' : 'Per Unit' }}
                            </span>
                        </td>
                        <td class="fw-semibold">
                            @if($ts->pricing_type === 'flat')
                            ₱{{ number_format($ts->flat_amount, 2) }}
                            @else
                            Lec: ₱{{ number_format($ts->lecture_rate, 2) }}<br>
                            <small class="text-muted">Lab: ₱{{ number_format($ts->lab_rate, 2) }}</small>
                            @endif
                        </td>
                        <td>₱{{ number_format($ts->misc_fee, 2) }}</td>
                        <td>₱{{ number_format($ts->reg_fee, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $ts->is_active ? 'success' : 'danger' }}">
                                {{ $ts->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route($showRoute, $ts) }}" class="btn btn-sm btn-outline-info" title="View"><i class="bi bi-eye"></i></a>
                                @if(!$isRegistrarView)
                                <a href="{{ route('admin.pricing.edit', $ts) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.pricing.toggle-active', $ts) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-{{ $ts->is_active ? 'secondary' : 'success' }}"
                                        title="{{ $ts->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="bi bi-{{ $ts->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.pricing.destroy', $ts) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-tags fs-2 d-block mb-2"></i>
                            No tuition structures found.
                            @if(!$isRegistrarView)
                            <a href="{{ route('admin.pricing.create') }}">Create one.</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($structures->hasPages())
    <div class="card-footer">{{ $structures->links() }}</div>
    @endif
</div>
@endsection