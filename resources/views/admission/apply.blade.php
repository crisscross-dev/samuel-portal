<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Admission - SCC Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/scc_logo.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/index.css'])

    <style>
        body {
            background-image: url('{{ asset("images/background.png") }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container py-3">

        <!-- Topbar -->
        <div class="topbar">
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-right-to-bracket"></i>
                Login
            </a>
        </div>

        <!-- Header -->
        <div class="header py-3">
            <div class="clinic-logo">
                <img src="{{ asset('images/scc_logo.png') }}" alt="SCC Logo" />
            </div>
            <h1>Samuel Christian College</h1>
            <h2>Admission Application</h2>
        </div>

        <!-- Form Section -->
        <div class="patient-section">
            <h2 class="section-title"><i class="fas fa-pen-to-square me-2"></i>Apply for Admission</h2>
            <p class="section-subtitle mb-4">Complete the form below to submit your application to SCC.</p>

            @if($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admission.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Personal Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-user me-1"></i> Personal Information</h6>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                            id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                            id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                            id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
                        @error('contact_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                            id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">— Select —</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="year_level" class="form-label">Year Level <span class="text-danger">*</span></label>
                        <select class="form-select @error('year_level') is-invalid @enderror" id="year_level" name="year_level" required>
                            <option value="">— Select —</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('year_level') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                        </select>
                        @error('year_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                        id="address" name="address" rows="2">{{ old('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr>

                {{-- Academic Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-book me-1"></i> Academic Information</h6>

                <div class="mb-3">
                    <label for="program_applied_id" class="form-label">Program Applied For <span class="text-danger">*</span></label>
                    <select class="form-select @error('program_applied_id') is-invalid @enderror"
                        id="program_applied_id" name="program_applied_id" required>
                        <option value="">— Select a Program —</option>
                        @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ old('program_applied_id') == $program->id ? 'selected' : '' }}>
                            {{ $program->code }} — {{ $program->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('program_applied_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr>

                {{-- Guardian Information --}}
                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-shield-halved me-1"></i> Guardian / Parent Information</h6>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="guardian_name" class="form-label">Guardian Name</label>
                        <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                            id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}">
                        @error('guardian_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="guardian_contact" class="form-label">Guardian Contact</label>
                        <input type="text" class="form-control @error('guardian_contact') is-invalid @enderror"
                            id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact') }}">
                        @error('guardian_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr>

                {{-- Document Upload --}}
                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-paperclip me-1"></i> Supporting Document</h6>

                <div class="mb-4">
                    <label for="document" class="form-label">Upload Document <span class="text-muted">(Optional — PDF, JPG, PNG, max 5MB)</span></label>
                    <input type="file" class="form-control @error('document') is-invalid @enderror"
                        id="document" name="document" accept=".pdf,.jpg,.jpeg,.png">
                    @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                    <i class="fas fa-paper-plane me-1"></i> Submit Application
                </button>
            </form>
        </div>

        <div class="text-center pb-3">
            <a href="{{ route('admission.track') }}" class="text-decoration-none small" style="color: rgba(255,255,255,0.9);">
                <i class="fas fa-magnifying-glass me-1"></i> Track Your Application
            </a>
            <span class="mx-2" style="color: rgba(255,255,255,0.5);">|</span>
            <a href="{{ route('login') }}" class="text-decoration-none small" style="color: rgba(255,255,255,0.9);">
                <i class="fas fa-right-to-bracket me-1"></i> Already have an account? Sign In
            </a>
        </div>
        <div class="text-center pb-3">
            <small style="color: rgba(255,255,255,0.6);">&copy; {{ date('Y') }} SCC Portal. All rights reserved.</small>
        </div>
    </div>
</body>

</html>