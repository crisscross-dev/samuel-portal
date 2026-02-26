@extends('layouts.app')
@section('title', 'Academic Years')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-calendar3 me-1"></i> Academic Years</h6>
        <a href="{{ route('admin.academic-years.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Add Academic Year
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th>Semesters</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($academicYears as $ay)
                        <tr>
                            <td class="fw-semibold">{{ $ay->name }}</td>
                            <td>{{ $ay->start_date->format('M d, Y') }}</td>
                            <td>{{ $ay->end_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge {{ $ay->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $ay->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @foreach($ay->semesters as $sem)
                                    <span class="badge {{ $sem->is_active ? 'bg-info' : 'bg-light text-dark' }}">{{ $sem->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.academic-years.edit', $ay) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.academic-years.destroy', $ay) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this academic year?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No academic years found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $academicYears->links() }}</div>
    </div>
</div>
@endsection
