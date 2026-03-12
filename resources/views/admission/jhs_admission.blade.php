@extends('layouts.app-index')

@section('title', 'JHS Admission Application - SCC Portal')

@push('styles')
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .form-label {
        font-weight: 600;
        font-size: 0.875rem;
    }

    .section-heading {
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #1e3a5f;
        border-left: 3px solid #f1c40f;
        padding-left: 0.6rem;
        margin-bottom: 1rem;
    }

    .step-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #1e3a5f, #1a5276);
        color: #f1c40f;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        margin-bottom: 0.4rem;
    }

    .privacy-box {
        background: #f8f9ff;
        border: 1px solid #d0d7f0;
        border-radius: 10px;
        padding: 1rem 1.25rem;
    }

    .privacy-box p {
        font-size: 0.83rem;
        color: #444;
        line-height: 1.65;
        text-align: justify;
        margin-bottom: 0.75rem;
    }

    .privacy-consent-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-top: 0.5rem;
        cursor: pointer;
        user-select: none;
    }

    .privacy-consent-row input[type="checkbox"] {
        width: 22px;
        height: 22px;
        min-width: 22px;
        cursor: pointer;
        margin-top: 2px;
        accent-color: #1e3a5f;
    }

    .privacy-consent-row label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #333;
        cursor: pointer;
        line-height: 1.5;
    }

    #privacy_error {
        display: none;
        color: #dc3545;
        font-size: 0.82rem;
        margin-top: 0.4rem;
    }

    .required-note {
        font-size: 0.78rem;
        color: #6b7280;
        margin-bottom: 1.25rem;
    }
</style>
@endpush

@section('content')

<div class="patient-section">

    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h2 class="section-title mb-1">
                <i class="fas fa-graduation-cap me-2"></i>JHS Admission Form
            </h2>
            <p class="section-subtitle mb-0">Fill out the form completely and submit. You will be notified of the next steps.</p>
        </div>
        <span class="step-badge"><i class="fas fa-school"></i> Grade 7 &ndash; 10</span>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="alert alert-danger py-2">
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <p class="required-note"><span class="text-danger">*</span> Required fields</p>

    <form method="POST" action="{{ route('admission.jhs.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- ═══ 1. STUDENT NAME ══════════════════════════════════════ --}}
        <div class="section-heading"><i class="fas fa-id-card me-1"></i> Student Name</div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="last_name" class="form-label">Surname <span class="text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('last_name') is-invalid @enderror"
                    id="last_name" name="last_name"
                    value="{{ old('last_name') }}"
                    placeholder="e.g. Santos"
                    required>
                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="first_name" class="form-label">Given Name <span class="text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('first_name') is-invalid @enderror"
                    id="first_name" name="first_name"
                    value="{{ old('first_name') }}"
                    placeholder="e.g. Maria"
                    required>
                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input type="text"
                    class="form-control @error('middle_name') is-invalid @enderror"
                    id="middle_name" name="middle_name"
                    value="{{ old('middle_name') }}"
                    placeholder="e.g. Reyes">
                @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="my-3">

        {{-- ═══ 2. GRADE LEVEL ═══════════════════════════════════════ --}}
        <div class="section-heading"><i class="fas fa-book me-1"></i> Grade Level Applying For</div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="program_applied_id" class="form-label">Grade Level <span class="text-danger">*</span></label>
                <select
                    class="form-select @error('program_applied_id') is-invalid @enderror"
                    id="program_applied_id" name="program_applied_id"
                    required
                    onchange="syncYearLevel(this)">
                    <option value="">— Select Grade Level —</option>
                    @foreach($jhsPrograms as $prog)
                    <option value="{{ $prog->id }}"
                        data-year="{{ (int) preg_replace('/\D/', '', $prog->code) }}"
                        {{ old('program_applied_id') == $prog->id ? 'selected' : '' }}>
                        {{ $prog->name }}
                    </option>
                    @endforeach
                </select>
                @error('program_applied_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        {{-- year_level is auto-synced from the selected program code --}}
        <input type="hidden" name="year_level" id="year_level" value="{{ old('year_level', 7) }}">

        <hr class="my-3">

        {{-- ═══ 3. PERSONAL INFORMATION ══════════════════════════════ --}}
        <div class="section-heading"><i class="fas fa-user me-1"></i> Personal Information</div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="lrn" class="form-label">LRN (Learner Reference Number)</label>
                <div class="input-group">
                    <input type="text"
                        class="form-control @error('lrn') is-invalid @enderror"
                        id="lrn" name="lrn"
                        value="{{ old('lrn') }}"
                        maxlength="12"
                        placeholder="12-digit number">
                    @error('lrn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-3">
                <label for="date_of_birth" class="form-label">Birthdate</label>
                <input type="date"
                    class="form-control @error('date_of_birth') is-invalid @enderror"
                    id="date_of_birth" name="date_of_birth"
                    value="{{ old('date_of_birth') }}">
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label for="age_display" class="form-label">Age</label>
                <input type="text" class="form-control bg-light" id="age_display" readonly placeholder="Auto-computed">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select @error('gender') is-invalid @enderror"
                    id="gender" name="gender">
                    <option value="">— Select —</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="nationality" class="form-label">Nationality</label>
                <input type="text"
                    class="form-control @error('nationality') is-invalid @enderror"
                    id="nationality" name="nationality"
                    value="{{ old('nationality', 'Filipino') }}">
                @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label for="religion" class="form-label">Religion</label>
                <input type="text"
                    class="form-control @error('religion') is-invalid @enderror"
                    id="religion" name="religion"
                    value="{{ old('religion') }}"
                    placeholder="e.g. Roman Catholic">
                @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="contact_number" class="form-label">Student's Contact No.</label>
                <div class="input-group">
                    <input type="text"
                        class="form-control @error('contact_number') is-invalid @enderror"
                        id="contact_number" name="contact_number"
                        value="{{ old('contact_number') }}"
                        placeholder="09XXXXXXXXX"
                        pattern="(09|\+639)[0-9]{9}"
                        title="Enter a valid PH mobile number, e.g. 09171234567"
                        maxlength="13">
                    @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Active Email Address <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="example@email.com"
                        required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Complete Address</label>
            <textarea
                class="form-control @error('address') is-invalid @enderror"
                id="address" name="address" rows="2"
                placeholder="House No., Street, Barangay, Municipality/City, Province">{{ old('address') }}</textarea>
            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="elementary_school" class="form-label">Elementary School Last Attended</label>
            <div class="input-group">
                <input type="text"
                    class="form-control @error('elementary_school') is-invalid @enderror"
                    id="elementary_school" name="elementary_school"
                    value="{{ old('elementary_school') }}"
                    placeholder="Name of elementary school">
                @error('elementary_school')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="my-3">

        {{-- ═══ 4. GUARDIAN INFORMATION ══════════════════════════════ --}}
        <div class="section-heading"><i class="fas fa-shield-halved me-1"></i> Guardian / Parent Information</div>

        <div class="mb-3">
            <label for="guardian_name" class="form-label">Guardian's Name</label>
            <div class="input-group">
                <input type="text"
                    class="form-control @error('guardian_name') is-invalid @enderror"
                    id="guardian_name" name="guardian_name"
                    value="{{ old('guardian_name') }}"
                    placeholder="Full name of parent or guardian">
                @error('guardian_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="guardian_contact" class="form-label">Guardian's Contact No.</label>
                <div class="input-group">
                    <input type="text"
                        class="form-control @error('guardian_contact') is-invalid @enderror"
                        id="guardian_contact" name="guardian_contact"
                        value="{{ old('guardian_contact') }}"
                        placeholder="09XXXXXXXXX"
                        pattern="(09|\+639)[0-9]{9}"
                        title="Enter a valid PH mobile number, e.g. 09171234567"
                        maxlength="13">
                    @error('guardian_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <label for="guardian_relationship" class="form-label">Relationship to the Student</label>
                <select class="form-select @error('guardian_relationship') is-invalid @enderror"
                    id="guardian_relationship" name="guardian_relationship">
                    <option value="">— Select —</option>
                    @foreach(['Mother','Father','Legal Guardian','Grandparent','Sibling','Uncle','Aunt','Others'] as $rel)
                    <option value="{{ $rel }}" {{ old('guardian_relationship') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                    @endforeach
                </select>
                @error('guardian_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <hr class="my-3">

        {{-- ═══ 5. DATA PRIVACY NOTICE ════════════════════════════════ --}}
        <div class="section-heading"><i class="fas fa-shield me-1"></i> Data Privacy Notice</div>

        <div class="privacy-box mb-4">
            <p>
                <em>I hereby certify that all information about my child stated above are true and correct.
                    I hereby give consent for my personal data entered herein to be processed for the purposes
                    of registration pursuant to <strong>RA 10173 – Data Privacy Act of 2012</strong>.</em>
            </p>
            <div class="privacy-consent-row" onclick="document.getElementById('privacy_consent').click()">
                <input type="checkbox" id="privacy_consent" name="privacy_consent" onclick="event.stopPropagation()">
                <label for="privacy_consent" style="pointer-events:none;">
                    I have read and agree to the Data Privacy Notice above.
                    <span class="text-danger">*</span>
                </label>
            </div>
            <div id="privacy_error"><i class="fas fa-exclamation-circle me-1"></i>You must agree to the Data Privacy Notice before submitting.</div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary w-100 fw-semibold py-2 fs-6">
            <i class="fas fa-paper-plane me-2"></i> Submit JHS Application
        </button>
    </form>

    <div class="text-center mt-3 mb-2">
        <a href="{{ route('admission.track') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.9);">
            <i class="fas fa-magnifying-glass me-1"></i> Track Your Application
        </a>
        <span class="mx-2" style="color:rgba(255,255,255,0.4);">|</span>
        <a href="{{ route('login') }}" class="text-decoration-none small" style="color:rgba(255,255,255,0.9);">
            <i class="fas fa-right-to-bracket me-1"></i> Already have an account? Sign In
        </a>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-compute age from birthdate
    document.getElementById('date_of_birth').addEventListener('change', function() {
        const dob = new Date(this.value);
        const ageEl = document.getElementById('age_display');
        if (isNaN(dob.getTime())) {
            ageEl.value = '';
            return;
        }
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
        ageEl.value = age >= 0 ? age + ' years old' : '';
    });

    // Sync year_level hidden field when grade level is selected
    function syncYearLevel(select) {
        const opt = select.options[select.selectedIndex];
        const year = opt ? (opt.getAttribute('data-year') || 7) : 7;
        document.getElementById('year_level').value = year;
    }

    // Block submit if privacy checkbox is unchecked
    document.querySelector('form').addEventListener('submit', function(e) {
        const cb = document.getElementById('privacy_consent');
        const err = document.getElementById('privacy_error');
        if (!cb.checked) {
            e.preventDefault();
            err.style.display = 'block';
            cb.closest('.privacy-consent-row').style.borderColor = '#dc3545';
            cb.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        } else {
            err.style.display = 'none';
            cb.closest('.privacy-consent-row').style.borderColor = '#ffc107';
        }
    });

    // Clear error when user checks the box
    document.getElementById('privacy_consent').addEventListener('change', function() {
        const err = document.getElementById('privacy_error');
        const row = this.closest('.privacy-consent-row');
        if (this.checked) {
            err.style.display = 'none';
            row.style.borderColor = '#198754';
        } else {
            row.style.borderColor = '#ffc107';
        }
    });
</script>
@endpush