<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCC Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/scc_logo.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite([
    'resources/css/app.css',
    'resources/css/index.css',
    ])

    <style>
        body {
            background-image: url('{{ asset("images/background.png") }}');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }

        .section-title {
            font-size: clamp(1.35rem, 2.5vw, 1.75rem);
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 0.35rem;
        }

        .section-subtitle {
            color: #6b7280;
            font-size: clamp(0.9rem, 1.5vw, 1rem);
            margin-bottom: 0;
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
            <h1>Samuel Christian College General Trias Inc.</h1>
            <h2>Student Portal</h2>
            <br>
            <p>School Management System</p>
            <p>Your Education, Our Commitment</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- CTA Section -->
        <div class="cta-section">
            <div class="cta-content">
                <h2 class="cta-title">Welcome to SCC Portal</h2>
                <p class="cta-subtitle">
                    Access your enrollment, grades, applications, and student information all in one place.
                </p>
                <div class="cta-buttons">
                    <a href="{{ route('admission.apply') }}" class="btn-cta btn-primary-cta">
                        <i class="fas fa-pen-to-square"></i>
                        Apply for Admission
                    </a>
                    <a href="{{ route('admission.track') }}" class="btn-cta btn-secondary-cta">
                        <i class="fas fa-magnifying-glass"></i>
                        Track Your Application
                    </a>
                    <a href="#services" onclick="scrollToServices()" class="btn-cta btn-secondary-cta">
                        <i class="fas fa-info-circle"></i>
                        Learn About Our Services
                    </a>
                </div>
            </div>
        </div>

        <!-- Feature Badges -->
        <div class="features-row">
            <div class="feature-badge">
                <i class="fas fa-graduation-cap"></i>
                <span>Quality Education</span>
            </div>
            <div class="feature-badge">
                <i class="fas fa-lock"></i>
                <span>Secure Records</span>
            </div>
            <div class="feature-badge">
                <i class="fas fa-bolt"></i>
                <span>Fast Access</span>
            </div>
        </div>

        <!-- Services Section -->
        <!-- <div class="patient-section" id="services">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Comprehensive healthcare services for Samuelians.</p>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-stethoscope"></i></div>
                    <h3>First Aid and Emergency Care</h3>
                    <p>Provides immediate care to students and staff in case of injuries, accidents, or sudden illnesses within the school premises.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-heart-pulse"></i></div>
                    <h3>Medical Consultation and Assessment</h3>
                    <p>Offers basic health check-ups, assessment of common ailments (e.g., fever, cough, colds, headache), and referral to higher medical facilities when necessary.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Health Monitoring and Surveillance</h3>
                    <p>Keeps track of students' health conditions (such as those with asthma, allergies, or chronic illnesses) and monitors any outbreak of communicable diseases.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-pills"></i></div>
                    <h3>Medication Administration</h3>
                    <p>Dispenses prescribed or over-the-counter medicines (with parental consent when required) for minor conditions such as pain, fever, or colds.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-weight-scale"></i></div>
                    <h3>Nutrition and Growth Monitoring</h3>
                    <p>Conducts weight, height, and BMI checks of students, and provides counseling on proper nutrition and healthy eating habits.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-comments"></i></div>
                    <h3>Health Counseling and Education</h3>
                    <p>Provides advice on hygiene, proper diet, stress management, reproductive health, and other age-appropriate health concerns.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-folder-open"></i></div>
                    <h3>Medical and Dental Record-Keeping</h3>
                    <p>Maintains updated health records of students and staff, including medical history, immunizations, and clinic visits.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-hospital"></i></div>
                    <h3>Referral Services</h3>
                    <p>Refers students or staff to hospitals, private doctors, or specialists for conditions beyond the scope of the school clinic.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-kit-medical"></i></div>
                    <h3>Health Emergency Preparedness</h3>
                    <p>Assists in disaster drills (earthquake, fire, typhoon response), ensuring readiness of first aid kits and medical supplies.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon"><i class="fas fa-hand-holding-heart"></i></div>
                    <h3>Wellness Promotion Activities</h3>
                    <p>Conducts seminars, campaigns, and activities promoting healthy lifestyles, mental wellness, and disease prevention.</p>
                </div>
            </div>
        </div> -->

    </div><!-- end .container -->

    @vite([
    'resources/js/app.js',
    'resources/js/index.js',
    ])

    @if(session('success'))
    <script>
        window.showFormOnLoad = true;
    </script>
    @endif
</body>

</html>