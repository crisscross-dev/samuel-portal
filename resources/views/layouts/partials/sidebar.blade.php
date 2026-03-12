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
        <a href="{{ route('registrar.exam-schedules.index') }}" class="nav-link {{ request()->routeIs('registrar.exam-schedules.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i> Exam Schedules
        </a>
        <a href="{{ route('registrar.students.index') }}" class="nav-link {{ request()->routeIs('registrar.students.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Students
        </a>

        <div class="nav-section">Academic Structure</div>
        <a href="{{ route('registrar.grade-levels.index') }}" class="nav-link {{ request()->routeIs('registrar.grade-levels.*') ? 'active' : '' }}">
            <i class="bi bi-list-ol"></i> Grade Levels
        </a>
        <a href="{{ route('registrar.subjects.index') }}" class="nav-link {{ request()->routeIs('registrar.subjects.*') ? 'active' : '' }}">
            <i class="bi bi-book"></i> Subjects
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