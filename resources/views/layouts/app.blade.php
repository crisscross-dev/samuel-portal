<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SCC Portal') - School Management Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #1a3a5c;
            --accent-color: #2c7be5;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; }
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--primary-color);
            color: #fff;
            z-index: 1030;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar .brand {
            padding: 1.25rem 1rem;
            font-size: 1.15rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.65rem 1rem;
            display: flex; align-items: center; gap: 0.65rem;
            font-size: 0.9rem;
            border-radius: 0.375rem;
            margin: 0.15rem 0.5rem;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }
        .sidebar .nav-section {
            padding: 0.75rem 1rem 0.25rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
        }
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #e3e6f0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .content-wrapper { padding: 1.5rem; }
        .stat-card {
            border: none; border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,0.08);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 0.5rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .table th { font-weight: 600; font-size: 0.85rem; text-transform: uppercase; color: #6c757d; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <i class="bi bi-mortarboard-fill"></i> SCC Portal
        </div>
        <div class="py-2">
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <div class="nav-section">Administration</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> User Management
                    </a>

                    <div class="nav-section">Faculty & Departments</div>
                    <a href="{{ route('admin.faculty.index') }}" class="nav-link {{ request()->routeIs('admin.faculty.*') ? 'active' : '' }}">
                        <i class="bi bi-person-workspace"></i> Faculty Management
                    </a>
                    <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Departments
                    </a>

                    <div class="nav-section">Academics</div>
                    <a href="{{ route('admin.academic-years.index') }}" class="nav-link {{ request()->routeIs('admin.academic-years.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i> Academic Years
                    </a>
                    <a href="{{ route('admin.grades.index') }}" class="nav-link {{ request()->routeIs('admin.grades.index') || request()->routeIs('admin.grades.reopen*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data"></i> Grades
                    </a>
                    <a href="{{ route('admin.grades.audit-log') }}" class="nav-link {{ request()->routeIs('admin.grades.audit-log') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Grade Audit Log
                    </a>
                    <a href="{{ route('admin.pricing.index') }}" class="nav-link {{ request()->routeIs('admin.pricing.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Tuition Pricing
                    </a>

                @elseif(auth()->user()->hasRole('registrar'))
                    <div class="nav-section">Registrar</div>
                    <a href="{{ route('registrar.dashboard') }}" class="nav-link {{ request()->routeIs('registrar.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('registrar.applications.index') }}" class="nav-link {{ request()->routeIs('registrar.applications.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-person"></i> Applications
                    </a>
                    <a href="{{ route('registrar.students.index') }}" class="nav-link {{ request()->routeIs('registrar.students.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Students
                    </a>

                    <div class="nav-section">Academic Structure</div>
                    <a href="{{ route('registrar.grade-levels.index') }}" class="nav-link {{ request()->routeIs('registrar.grade-levels.*') ? 'active' : '' }}">

                        <i class="bi bi-list-ol"></i> Grade Levels
                    </a>
                    <a href="{{ route('registrar.sections.index') }}" class="nav-link {{ request()->routeIs('registrar.sections.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> Sections
                    </a>

                    <div class="nav-section">Enrollment</div>
                    <a href="{{ route('registrar.enrollments.index') }}" class="nav-link {{ request()->routeIs('registrar.enrollments.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-check"></i> Enrollments
                    </a>
                    <a href="{{ route('registrar.payments.index') }}" class="nav-link {{ request()->routeIs('registrar.payments.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card"></i> Payments
                    </a>
                    <a href="{{ route('registrar.grades.index') }}" class="nav-link {{ request()->routeIs('registrar.grades.index') || request()->routeIs('registrar.grades.reopen*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-data"></i> Grades
                    </a>
                    <a href="{{ route('registrar.grades.audit-log') }}" class="nav-link {{ request()->routeIs('registrar.grades.audit-log') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Grade Audit Log
                    </a>
                    <a href="{{ route('registrar.pricing.index') }}" class="nav-link {{ request()->routeIs('registrar.pricing.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Tuition Pricing
                    </a>

                @elseif(auth()->user()->hasRole('faculty'))
                    <div class="nav-section">Faculty</div>
                    <a href="{{ route('faculty.dashboard') }}" class="nav-link {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('faculty.sections.index') }}" class="nav-link {{ request()->routeIs('faculty.sections.*') || request()->routeIs('faculty.grades.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> Teaching Loads
                    </a>

                @elseif(auth()->user()->hasRole('student'))
                    <div class="nav-section">Student Portal</div>
                    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('student.enrollment') }}" class="nav-link {{ request()->routeIs('student.enrollment') ? 'active' : '' }}">
                        <i class="bi bi-journal-check"></i> Enrollment
                    </a>
                    <a href="{{ route('student.schedule') }}" class="nav-link {{ request()->routeIs('student.schedule') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i> Schedule
                    </a>
                    <a href="{{ route('student.grades') }}" class="nav-link {{ request()->routeIs('student.grades') ? 'active' : '' }}">
                        <i class="bi bi-card-checklist"></i> Grades
                    </a>
                @endif
            @endauth
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="topbar">
            <div>
                <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <span class="fw-semibold ms-2">@yield('title', 'Dashboard')</span>
            </div>
            @auth
                <div class="dropdown">
                    <a href="#" class="text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->primaryRole()?->name }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>

        <div class="content-wrapper">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
