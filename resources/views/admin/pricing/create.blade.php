@extends('layouts.app')
@section('title', 'New Tuition Structure')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-tags me-1"></i> Create Tuition Structure</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle me-2"></i>
                    Creating a new structure for a department + academic year will <strong>deactivate</strong> any existing active structure for that combination. Historical enrollments remain unaffected.
                </div>

                <form method="POST" action="{{ route('admin.pricing.store') }}" id="pricingForm">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">— Select —</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
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
                                    <option value="{{ $ay->id }}" {{ old('academic_year_id') == $ay->id ? 'selected' : '' }}>
                                        {{ $ay->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pricing Type <span class="text-danger">*</span></label>
                            <select name="pricing_type" id="pricingType" class="form-select @error('pricing_type') is-invalid @enderror" required>
                                <option value="flat" {{ old('pricing_type', 'flat') === 'flat' ? 'selected' : '' }}>Flat Rate (JHS / SHS)</option>
                                <option value="per_unit" {{ old('pricing_type') === 'per_unit' ? 'selected' : '' }}>Per Unit (College)</option>
                            </select>
                            @error('pricing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-book me-1"></i> Tuition Rate</h6>

                        {{-- Flat rate fields --}}
                        <div id="flatFields">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Flat Tuition Amount <span class="text-danger">*</span></label>
                                <div class="input-group" style="max-width:280px">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="flat_amount" class="form-control @error('flat_amount') is-invalid @enderror"
                                        value="{{ old('flat_amount') }}" step="0.01" min="0" placeholder="e.g. 5000.00">
                                </div>
                                @error('flat_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                <small class="text-muted">Fixed tuition cost per enrollment regardless of subjects taken.</small>
                            </div>
                        </div>

                        {{-- Per-unit fields --}}
                        <div id="perUnitFields" style="display:none">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Lecture Unit Rate <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="lecture_rate" class="form-control @error('lecture_rate') is-invalid @enderror"
                                            value="{{ old('lecture_rate', 1500) }}" step="0.01" min="0">
                                    </div>
                                    @error('lecture_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Cost per lecture unit</small>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Lab Unit Rate <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" name="lab_rate" class="form-control @error('lab_rate') is-invalid @enderror"
                                            value="{{ old('lab_rate', 2000) }}" step="0.01" min="0">
                                    </div>
                                    @error('lab_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Cost per lab unit</small>
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
                                        value="{{ old('misc_fee', 3000) }}" step="0.01" min="0" required>
                                </div>
                                @error('misc_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Fixed miscellaneous fee per enrollment</small>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Registration Fee <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="reg_fee" class="form-control @error('reg_fee') is-invalid @enderror"
                                        value="{{ old('reg_fee', 500) }}" step="0.01" min="0" required>
                                </div>
                                @error('reg_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Fixed registration fee per enrollment</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label <small class="text-muted">(optional)</small></label>
                        <input type="text" name="label" class="form-control @error('label') is-invalid @enderror"
                            value="{{ old('label') }}" placeholder="e.g. JHS 2025–2026 Flat Rate">
                        @error('label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Create Structure</button>
                        <a href="{{ route('admin.pricing.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info panel --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-question-circle me-1"></i> Pricing Types</h6></div>
            <div class="card-body small">
                <p class="fw-semibold text-info mb-1"><i class="bi bi-square-fill me-1"></i> Flat Rate</p>
                <p class="text-muted mb-3">A fixed tuition cost per enrollment period. Suitable for JHS and SHS where tuition is not based on units taken.<br><strong>Total = Flat Amount + Misc + Reg</strong></p>
                <p class="fw-semibold text-primary mb-1"><i class="bi bi-square-fill me-1"></i> Per Unit</p>
                <p class="text-muted mb-0">Tuition computed based on subjects enrolled. Suitable for College programs.<br><strong>Total = (Lec Units × Rate) + (Lab Units × Rate) + Misc + Reg</strong></p>
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
    togglePricingFields(); // run on load
</script>
@endsection
