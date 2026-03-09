<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SCC Portal')</title>
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

        .sticky-topbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.06);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sticky-topbar .topbar {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.5rem 1.5rem;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Sticky Topbar / Navbar -->
    <div class="sticky-topbar">
        <div class="topbar">
            <!-- Nav Links -->
            <nav class="topbar-nav">
                <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    Home
                </a>

                <!-- Admission Dropdown -->
                <div class="nav-dropdown">
                    <button class="nav-item nav-dropdown-toggle" type="button">
                        Admission <i class="fas fa-chevron-down nav-chevron"></i>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('landing.jhs') }}" class="nav-dropdown-item">
                            <i class="fas fa-pen-to-square"></i> Junior High
                        </a>
                        <a href="{{ route('landing.shs') }}" class="nav-dropdown-item">
                            <i class="fas fa-magnifying-glass"></i> Senior High
                        </a>
                        <a href="{{ route('landing.college') }}" class="nav-dropdown-item">
                            <i class="fas fa-magnifying-glass"></i> College
                        </a>
                    </div>
                </div>

                <!-- School Officials Dropdown -->
                <div class="nav-dropdown">
                    <button class="nav-item nav-dropdown-toggle" type="button">
                        School Officials <i class="fas fa-chevron-down nav-chevron"></i>
                    </button>
                    <div class="nav-dropdown-menu nav-dropdown-menu--wide">

                        <div class="nav-dropdown-group-label">School Officials</div>
                        <a href="{{ route('landing.school_admin') }}" class="nav-dropdown-item">
                            <i class="fas fa-user-tie"></i> School Administrators
                        </a>
                        <a href="{{ route('landing.aass_admin') }}" class="nav-dropdown-item">
                            <i class="fas fa-users"></i> AASS Admin &amp; Staffs
                        </a>
                        <a href="{{ route('landing.sas_admin') }}" class="nav-dropdown-item">
                            <i class="fas fa-users"></i> SAS Admin &amp; Staffs
                        </a>
                        <a href="{{ route('landing.hed_admin') }}" class="nav-dropdown-item">
                            <i class="fas fa-users"></i> HED Admin &amp; Staffs
                        </a>
                        <a href="{{ route('landing.bed_admin') }}" class="nav-dropdown-item">
                            <i class="fas fa-users"></i> BED Admin &amp; Staffs
                        </a>
                    </div>
                </div>

                <!-- More Dropdown -->
                <div class="nav-dropdown">
                    <button class="nav-item nav-dropdown-toggle" type="button">
                        More <i class="fas fa-chevron-down nav-chevron"></i>
                    </button>
                    <div class="nav-dropdown-menu nav-dropdown-menu--wide">

                        <div class="nav-dropdown-group-label">About Us</div>
                        <a href="{{ route('landing.school_profile') }}" class="nav-dropdown-item">
                            <i class="fas fa-school"></i> School Profile
                        </a>
                        <a href="{{ route('landing.vision_mission') }}" class="nav-dropdown-item">
                            <i class="fas fa-eye"></i> Vision &amp; Mission
                        </a>
                        <a href="{{ route('landing.core_values_goals') }}" class="nav-dropdown-item">
                            <i class="fas fa-star"></i> Core Values &amp; Goals
                        </a>
                        <a href="{{ route('landing.educational_philosophy') }}" class="nav-dropdown-item">
                            <i class="fas fa-book-open"></i> Educational Philosophy
                        </a>
                        <a href="{{ route('landing.clubs') }}" class="nav-dropdown-item">
                            <i class="fas fa-people-group"></i> Clubs &amp; Organizations
                        </a>
                        <a href="{{ route('landing.virtual_tour') }}" class="nav-dropdown-item">
                            <i class="fas fa-map"></i> Virtual Tour
                        </a>

                        <div class="nav-dropdown-divider"></div>

                        <a href="{{ route('landing.contact_us') }}" class="nav-dropdown-item">
                            <i class="fas fa-envelope"></i> Contact Us
                        </a>

                    </div>
                </div>
            </nav>

            <!-- Login Button -->
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-right-to-bracket"></i>
                Login
            </a>
        </div>
    </div>

    <div class="container py-3">

        <!-- Header -->
        <div class="header py-3">
            <div class="clinic-logo">
                <img src="{{ asset('images/scc_logo.png') }}" alt="SCC Logo" />
            </div>
            <h1>Samuel Christian College General Trias Inc.</h1>
            <!-- <h2>Student Portal</h2>
            <br>
            <p>School Management System</p>
            <p>Your Education, Our Commitment</p> -->
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')

    </div><!-- end .container -->

    @vite([
    'resources/js/app.js',
    'resources/js/index.js',
    ])

    @stack('scripts')
</body>

</html>