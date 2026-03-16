<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guidance Interview Form - SCC Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            min-height: 100vh;
        }

        .form-shell {
            max-width: 980px;
            margin: 32px auto;
        }
    </style>
</head>

<body>
    @php
    $form = $application->interview_form_data ?? [];
    $isShs = ($formType ?? 'jhs') === 'shs';
    $isGrade12 = (int) $application->year_level === 12;
    @endphp
    <div class="container form-shell">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-white py-4">
                <h3 class="mb-1">{{ $isShs ? 'SHS' : 'JHS' }} Guidance Form</h3>
                <p class="text-muted mb-0">Complete the remaining details before your scheduled interview on {{ $application->interview_date?->format('F d, Y') ?: 'the assigned date' }}.</p>
            </div>
            <div class="card-body p-4 p-lg-5">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
                @endif

                <div class="alert alert-info small">
                    Admission information is pre-filled from your application so you do not need to retype it.
                </div>

                <form method="POST" action="{{ route('admission.interview-form.submit', $application->interview_form_token) }}">
                    @csrf

                    <h6 class="fw-bold mb-3">Enrollment Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Date of Enrollment</label>
                            <input type="date" name="date_of_enrollment" class="form-control" value="{{ old('date_of_enrollment', $form['date_of_enrollment'] ?? now()->toDateString()) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Incoming Grade Level</label>
                            <input type="text" class="form-control" value="Grade {{ $application->year_level }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Student Classification</label>
                            <input type="text" name="student_classification" class="form-control" value="{{ old('student_classification', $form['student_classification'] ?? '') }}" required>
                        </div>

                        @if($isShs)
                        @if($isGrade12)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Strand</label>
                            <select name="strand" class="form-select" required>
                                <option value="">Select Strand</option>
                                @foreach(['ABM', 'HUMSS', 'ICT', 'STEM'] as $strand)
                                <option value="{{ $strand }}" {{ old('strand', $form['strand'] ?? '') === $strand ? 'selected' : '' }}>{{ $strand }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Last Year &amp; Section</label>
                            <input type="text" name="last_year_section" class="form-control" value="{{ old('last_year_section', $form['last_year_section'] ?? '') }}" required>
                        </div>
                        @else
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Elective Course</label>
                            <input type="text" name="elective_course" class="form-control" value="{{ old('elective_course', $form['elective_course'] ?? ($application->program->name ?? '')) }}" required>
                        </div>
                        @endif
                        @else
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Previous School Classification</label>
                            <select name="previous_school_classification" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Private School" {{ old('previous_school_classification', $form['previous_school_classification'] ?? '') === 'Private School' ? 'selected' : '' }}>Private School</option>
                                <option value="Public School" {{ old('previous_school_classification', $form['previous_school_classification'] ?? '') === 'Public School' ? 'selected' : '' }}>Public School</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ESC Grantee</label>
                            <select name="esc_grantee" class="form-select" required>
                                <option value="">Select</option>
                                <option value="yes" {{ old('esc_grantee', $form['esc_grantee'] ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('esc_grantee', $form['esc_grantee'] ?? '') === 'no' ? 'selected' : '' }}>No</option>
                                <option value="not_applicable" {{ old('esc_grantee', $form['esc_grantee'] ?? '') === 'not_applicable' ? 'selected' : '' }}>Not Applicable (Grade 7)</option>
                            </select>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type of Subsidy</label>
                            <input type="text" name="type_of_subsidy" class="form-control" value="{{ old('type_of_subsidy', $form['type_of_subsidy'] ?? '') }}">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Student Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3"><label class="form-label">Last Name</label><input type="text" class="form-control" value="{{ $application->last_name }}" readonly></div>
                        <div class="col-md-3"><label class="form-label">Given Name</label><input type="text" class="form-control" value="{{ $application->first_name }}" readonly></div>
                        <div class="col-md-3"><label class="form-label">Middle Name</label><input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $application->middle_name) }}"></div>
                        <div class="col-md-3"><label class="form-label">Extension Name</label><input type="text" name="extension_name" class="form-control" value="{{ old('extension_name', $form['extension_name'] ?? '') }}"></div>

                        <div class="col-md-3"><label class="form-label">LRN</label><input type="text" name="lrn" class="form-control" value="{{ old('lrn', $application->lrn) }}"></div>
                        <div class="col-md-3"><label class="form-label">Gender</label><input type="text" class="form-control" value="{{ ucfirst((string) $application->gender) }}" readonly></div>
                        <div class="col-md-3"><label class="form-label">Contact No.</label><input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $application->contact_number) }}" required></div>
                        <div class="col-md-3"><label class="form-label">Birthdate</label><input type="text" class="form-control" value="{{ $application->date_of_birth?->format('Y-m-d') }}" readonly></div>

                        <div class="col-md-6"><label class="form-label">Place of Birth</label><input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth', $form['place_of_birth'] ?? '') }}" required></div>
                        <div class="col-md-3"><label class="form-label">Nationality</label><input type="text" name="nationality" class="form-control" value="{{ old('nationality', $application->nationality) }}" required></div>
                        <div class="col-md-3"><label class="form-label">Religion</label><input type="text" name="religion" class="form-control" value="{{ old('religion', $application->religion) }}"></div>

                        <div class="col-12"><label class="form-label">Residential Address</label><textarea name="address" class="form-control" rows="2" required>{{ old('address', $application->address) }}</textarea></div>
                        <div class="col-12"><label class="form-label">{{ $isShs ? 'Junior High School Last Attended' : 'Elementary School Last Attended' }}</label><input type="text" name="elementary_school" class="form-control" value="{{ old('elementary_school', $application->elementary_school) }}" required></div>
                    </div>

                    <h6 class="fw-bold mb-3">Parent / Guardian Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6"><label class="form-label">Father's Name</label><input type="text" name="father_name" class="form-control" value="{{ old('father_name', $form['father_name'] ?? '') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Father's Contact No.</label><input type="text" name="father_contact" class="form-control" value="{{ old('father_contact', $form['father_contact'] ?? '') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Mother's Name</label><input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $form['mother_name'] ?? '') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Mother's Contact No.</label><input type="text" name="mother_contact" class="form-control" value="{{ old('mother_contact', $form['mother_contact'] ?? '') }}" required></div>
                        <div class="col-md-4"><label class="form-label">Guardian's Name</label><input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $application->guardian_name) }}" required></div>
                        <div class="col-md-4"><label class="form-label">Guardian's Contact No.</label><input type="text" name="guardian_contact" class="form-control" value="{{ old('guardian_contact', $application->guardian_contact) }}" required></div>
                        <div class="col-md-4"><label class="form-label">Relationship</label><input type="text" name="guardian_relationship" class="form-control" value="{{ old('guardian_relationship', $application->guardian_relationship) }}" required></div>
                    </div>

                    @if(!$isShs)
                    <h6 class="fw-bold mb-3">JHS Preference</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6"><label class="form-label">Preferred Interview Date</label><input type="date" name="preferred_interview_date" class="form-control" value="{{ old('preferred_interview_date', $form['preferred_interview_date'] ?? '') }}"></div>
                        <div class="col-md-6"><label class="form-label">Preferred Interview Time</label><input type="text" name="preferred_interview_time" class="form-control" value="{{ old('preferred_interview_time', $form['preferred_interview_time'] ?? '') }}" placeholder="e.g. 9:00 AM"></div>
                    </div>
                    @endif

                    <div class="mt-4 d-flex justify-content-end"><button type="submit" class="btn btn-primary px-4">Submit Form</button></div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>