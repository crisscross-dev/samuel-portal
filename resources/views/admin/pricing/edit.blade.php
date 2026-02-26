@extends('layouts.app')
@section('title', 'Edit Tuition Structure')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-pencil me-1"></i> Edit Tuition Structure</h6>
                <span class="badge bg-{{ $pricing->is_active ? 'success' : 'danger' }}">
                    {{ $pricing->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div class="alert alert-warning small">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Saving will create a fresh revision. The previous active structure for this department + academic year will be deactivated. Finalized enrollments are not affected.
                </div>

                <form method="POST" action="{{ route('admin.pricing.update', $pricing) }}">
                    @csrf @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">— Select —</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $pricing->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->code }} — {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                <option value="">— Select —</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ old('academic_year_id', $pricing->academic_year_id) == $ay->id ? 'selected' : '' }}>
                                        {{ $ay->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pricing Type <span class="text-danger">*</span></label>
                            <select name="pricing_type" id="pricingType" class="form-select @error('pricing_type') is-invalid @enderror" required>
                                <option value="flat" {{ old('pricing_type', $pricing->pricing_type) === 'flat' ? 'selected' : '' }}>Flat Rate (JHS / SHS)</option>
                                <option value="per_unit" {{ old('pricing_type', $pricing->pricing_type) === 'per_unit' ? 'selected' : '' }}>Per Unit (College)</option>
                            </select>
                            @error('pricing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-book me-1"></i> Tuition Rate</h6>

                        <div id="flatFields">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Flat Tuition Amount <span class="text-danger">*</span></label>
                                <div class="input-group" style="max-width:280px">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="flat_amount" class="form-control @error('flat_amount') is-invalid @enderror"
                                        value="{{ old('flat_amount', $pricing->flat_amount) }}" step="0.01" min="0">
                                </div>
                                @error('flat_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div id="perUnitFields" style="display:none">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Lecture Unit Rate <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="lecture_rate" class="form-control @error('lecture_rate') is-invalid @enderror"
                                            value="{{ old('lecture_rate', $pricing->lecture_rate) }}" step="0.01" min="0">
                                    </div>
                                    @error('lecture_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Lab Unit Rate <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="lab_rate" class="form-control @error('lab_rate') is-invalid @enderror"
                                            value="{{ old('lab_rate', $pricing->lab_rate) }}" step="0.01" min="0">
                                    </div>
                                    @error('lab_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-receipt me-1"></i> Fixed Fees</h6>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Miscellaneous Fee <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="misc_fee" class="form-control @error('misc_fee') is-invalid @enderror"
                                        value="{{ old('misc_fee', $pricing->misc_fee) }}" step="0.01" min="0" required>
                                </div>
                                @error('misc_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Registration Fee <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="reg_fee" class="form-control @error('reg_fee') is-invalid @enderror"
                                        value="{{ old('reg_fee', $pricing->reg_fee) }}" step="0.01" min="0" required>
                                </div>
                                @error('reg_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label <small class="text-muted">(optional)</small></label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                            value="{{ old('label', $pricing->label) }}">
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
                        <a href="{{ route('admin.pricing.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-info-circle me-1"></i> Current Structure</h6></div>
            <div class="card-body small">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted">Department</th><td>{{ $pricing->department->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Academic Year</th><td>{{ $pricing->academicYear->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Type</th><td><span class="badge bg-{{ $pricing->pricing_type === 'flat' ? 'info text-dark' : 'primary' }}">{{ $pricing->pricing_type === 'flat' ? 'Flat' : 'Per Unit' }}</span></td></tr>
                    <tr><th class="text-muted">Linked Enrollments</th><td>{{ $pricing->enrollments()->count() }}</td></tr>
                    <tr><th class="text-muted">Created</th><td>{{ $pricing->created_at->format('M d, Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const pricingType = document.getElementById('pricingType');
    const flatFields = document.getElementById('flatFields');
    const perUnitFields = document.getElementById('perUnitFields');

    function togglePricingFields() {
        const isFlat = pricingType.value === 'flat';
        flatFields.style.display = isFlat ? '' : 'none';
        perUnitFields.style.display = isFlat ? 'none' : '';
    }

    pricingType.addEventListener('change', togglePricingFields);
    togglePricingFields();
</script>
@endsection
