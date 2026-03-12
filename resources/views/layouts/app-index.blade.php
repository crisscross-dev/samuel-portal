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
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                "Helvetica Neue", Arial, sans-serif !important;
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

    @unless(View::hasSection('hide_footer'))
    <!-- ── Site Footer ── -->
    <footer class="scc-footer">
        <div class="scc-footer-inner">

            <div class="scc-footer-brand">
                <img src="{{ asset('images/scc_logo.png') }}" alt="SCC Logo" class="scc-footer-logo">
                <div>
                    <div class="scc-footer-school-name">Samuel Christian College</div>
                    <div class="scc-footer-school-sub">General Trias, Cavite</div>
                    <p class="scc-footer-tagline">Your Education, Our Commitment</p>
                </div>
            </div>

            <div class="scc-footer-columns">

                <div class="scc-footer-col">
                    <h4 class="scc-footer-col-title">Junior High School</h4>
                    <ul class="scc-footer-list">
                        <li><i class="fas fa-phone"></i> (046) 402-0725</li>
                        <li><i class="fas fa-mobile-screen-button"></i> 0916 729 5830</li>
                        <li><i class="fab fa-facebook-messenger"></i> Scc Jhs Registrar</li>
                        <li><i class="fas fa-envelope"></i> sccjhsdepartment@gmail.com</li>
                    </ul>
                </div>

                <div class="scc-footer-col">
                    <h4 class="scc-footer-col-title">Senior High School</h4>
                    <ul class="scc-footer-list">
                        <li><i class="fas fa-phone"></i> (046) 402-0725</li>
                        <li><i class="fas fa-mobile-screen-button"></i> 0916 729 5830</li>
                        <li><i class="fab fa-facebook-messenger"></i> Scc Shs Registrar</li>
                        <li><i class="fas fa-envelope"></i> scc.shsregistrar@gmail.com</li>
                    </ul>
                </div>

                <div class="scc-footer-col">
                    <h4 class="scc-footer-col-title">College</h4>
                    <ul class="scc-footer-list">
                        <li><i class="fas fa-mobile-screen-button"></i> 0956 863 3828</li>
                        <li><i class="fas fa-phone"></i> (046) 456-9955</li>
                        <li><i class="fas fa-envelope"></i> sccgticollegedepartment@gmail.com</li>
                    </ul>
                </div>

                <div class="scc-footer-col">
                    <h4 class="scc-footer-col-title">Cashier</h4>
                    <ul class="scc-footer-list">
                        <li><i class="fas fa-phone"></i> (046) 402-0725</li>
                        <li><i class="fas fa-mobile-screen-button"></i> 0995-988 1911</li>
                    </ul>
                </div>

                <div class="scc-footer-col">
                    <h4 class="scc-footer-col-title">Guidance</h4>
                    <ul class="scc-footer-list">
                        <li><i class="fas fa-phone"></i> (046) 509-7310</li>
                        <li><i class="fas fa-phone"></i> (046) 509-8481</li>
                        <li><i class="fas fa-mobile-screen-button"></i> 0953-376-9919</li>
                    </ul>
                </div>

                <div class="scc-footer-col scc-footer-col--hours">
                    <h4 class="scc-footer-col-title">Office Hours</h4>
                    @php $footerDow = now()->dayOfWeek; @endphp
                    <table class="scc-footer-hours">
                        <tr class="{{ $footerDow === 1 ? 'scc-hours-today' : '' }}">
                            <td>Mon</td>
                            <td>09:00 am – 05:00 pm</td>
                        </tr>
                        <tr class="{{ $footerDow === 2 ? 'scc-hours-today' : '' }}">
                            <td>Tue</td>
                            <td>09:00 am – 05:00 pm</td>
                        </tr>
                        <tr class="{{ $footerDow === 3 ? 'scc-hours-today' : '' }}">
                            <td>Wed</td>
                            <td>09:00 am – 05:00 pm</td>
                        </tr>
                        <tr class="{{ $footerDow === 4 ? 'scc-hours-today' : '' }}">
                            <td>Thu</td>
                            <td>09:00 am – 05:00 pm</td>
                        </tr>
                        <tr class="{{ $footerDow === 5 ? 'scc-hours-today' : '' }}">
                            <td>Fri</td>
                            <td>09:00 am – 05:00 pm</td>
                        </tr>
                        <tr class="{{ $footerDow === 6 ? 'scc-hours-today' : '' }}">
                            <td>Sat</td>
                            <td>Closed</td>
                        </tr>
                        <tr class="{{ $footerDow === 0 ? 'scc-hours-today' : '' }}">
                            <td>Sun</td>
                            <td>Closed</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>

        <div class="scc-footer-bottom">
            <span>Blessed day! &nbsp;|&nbsp; Thank you and God bless! &nbsp;|&nbsp; &copy; {{ date('Y') }} Samuel Christian College General Trias Inc. All rights reserved.</span>
        </div>
    </footer>
    @endunless

    <style>
        .scc-footer {
            background: linear-gradient(135deg, #0d1f3c 0%, #1e3a5f 100%);
            color: #cbd5e1;
            margin-top: 2rem;
            font-size: 0.85rem;
        }

        .scc-footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .scc-footer-brand {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            flex: 0 0 220px;
        }

        .scc-footer-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .scc-footer-school-name {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .scc-footer-school-sub {
            font-size: 0.78rem;
            color: #94a3b8;
            margin-bottom: 0.4rem;
        }

        .scc-footer-tagline {
            font-size: 0.75rem;
            color: #64748b;
            font-style: italic;
            margin: 0;
        }

        .scc-footer-columns {
            flex: 1 1 0;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem 2rem;
        }

        .scc-footer-col {
            flex: 1 1 160px;
            min-width: 140px;
        }

        .scc-footer-col-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0 0 0.65rem;
            padding-bottom: 0.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .scc-footer-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .scc-footer-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            color: #94a3b8;
            line-height: 1.4;
            word-break: break-word;
        }

        .scc-footer-list li i {
            color: #3b82f6;
            font-size: 0.8rem;
            margin-top: 0.18rem;
            flex-shrink: 0;
            width: 14px;
            text-align: center;
        }

        .scc-footer-col--hours {
            flex: 0 0 auto;
            min-width: 170px;
        }

        .scc-footer-hours {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .scc-footer-hours td {
            padding: 0.18rem 0.5rem 0.18rem 0;
            color: #94a3b8;
            vertical-align: top;
        }

        .scc-footer-hours td:first-child {
            font-weight: 700;
            color: #cbd5e1;
            width: 2.6rem;
        }

        .scc-footer-hours tr.scc-hours-today td {
            color: #fbbf24;
            font-weight: 700;
        }

        .scc-footer-bottom {
            background: rgba(0, 0, 0, 0.25);
            text-align: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.75rem;
            color: #64748b;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        @media (max-width: 768px) {
            .scc-footer-inner {
                flex-direction: column;
            }

            .scc-footer-brand {
                flex: unset;
            }
        }
    </style>
</body>

</html>