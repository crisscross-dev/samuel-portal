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
            max-width: 880px;
            margin: 32px auto;
        }
    </style>
</head>

<body>
    <div class="container form-shell">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-white py-4">
                <h3 class="mb-1">Guidance Interview Form</h3>
                <p class="text-muted mb-0">Complete the remaining admission details before your scheduled interview on {{ $application->interview_date?->format('F d, Y') ?: 'the assigned date' }}.</p>
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

                <div class="row g-4 mb-4">
                    <div class="col-md-6"><label class="form-label text-muted small fw-semibold">First Name</label><input type="text" class="form-control" value="{{ $application->first_name }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted small fw-semibold">Last Name</label><input type="text" class="form-control" value="{{ $application->last_name }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted small fw-semibold">Email</label><input type="text" class="form-control" value="{{ $application->email }}" readonly></div>
                    <div class="col-md-6"><label class="form-label text-muted small fw-semibold">Program Applied</label><input type="text" class="form-control" value="{{ $application->program->name ?? 'N/A' }}" readonly></div>
                </div>

                <form method="POST" action="{{ route('admission.interview-form.submit', $application->interview_form_token) }}">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-4"><label class="form-label fw-semibold">Middle Name</label><input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $application->middle_name) }}"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">LRN</label><input type="text" name="lrn" class="form-control" value="{{ old('lrn', $application->lrn) }}"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Contact Number</label><input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $application->contact_number) }}" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="3" required>{{ old('address', $application->address) }}</textarea></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Nationality</label><input type="text" name="nationality" class="form-control" value="{{ old('nationality', $application->nationality) }}" required></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Religion</label><input type="text" name="religion" class="form-control" value="{{ old('religion', $application->religion) }}"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Guardian Name</label><input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $application->guardian_name) }}" required></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Guardian Contact</label><input type="text" name="guardian_contact" class="form-control" value="{{ old('guardian_contact', $application->guardian_contact) }}" required></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Relationship</label><input type="text" name="guardian_relationship" class="form-control" value="{{ old('guardian_relationship', $application->guardian_relationship) }}" required></div>
                        <div class="col-12"><label class="form-label fw-semibold">Elementary School</label><input type="text" name="elementary_school" class="form-control" value="{{ old('elementary_school', $application->elementary_school) }}" required></div>
                    </div>
                    <div class="mt-4 d-flex justify-content-end"><button type="submit" class="btn btn-primary px-4">Submit Form</button></div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>