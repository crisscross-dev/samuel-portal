<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\FacultyController as AdminFacultyController;
use App\Http\Controllers\Admin\GradeController as AdminGradeController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboard;
use App\Http\Controllers\Faculty\GradeController;
use App\Http\Controllers\Faculty\SectionController as FacultySectionController;
use App\Http\Controllers\Registrar\ApplicationController;
use App\Http\Controllers\Registrar\DashboardController as RegistrarDashboard;
use App\Http\Controllers\Registrar\EnrollmentController;
use App\Http\Controllers\Registrar\GradeLevelController;
use App\Http\Controllers\Registrar\PaymentController;
use App\Http\Controllers\Registrar\SectionController;
use App\Http\Controllers\Registrar\StudentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollmentController;
use App\Http\Controllers\Student\GradeController as StudentGradeController;
use App\Http\Controllers\Student\ScheduleController;
use Illuminate\Support\Facades\Route;

// ─── Public / Auth ────────────────────────────────────────────────

Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Public Admission ─────────────────────────────────────────────

Route::prefix('admission')->name('admission.')->group(function () {
    Route::get('/apply',   [AdmissionController::class, 'create'])->name('apply');
    Route::post('/apply',  [AdmissionController::class, 'store'])->name('store');
    Route::get('/success', [AdmissionController::class, 'success'])->name('success');
    Route::get('/track',   [AdmissionController::class, 'track'])->name('track');
    Route::post('/track',  [AdmissionController::class, 'trackSearch'])->name('track.search');
});

// ─── Admin Routes ─────────────────────────────────────────────────

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // User management
        Route::resource('users', UserController::class);

        // Academic year management
        Route::resource('academic-years', AcademicYearController::class);

        // Pricing / Tuition Structure management
        Route::resource('pricing', PricingController::class);
        Route::patch('/pricing/{pricing}/toggle-active', [PricingController::class, 'toggleActive'])->name('pricing.toggle-active');

        // Faculty management (Admin only — creates user + faculty accounts)
        Route::resource('faculty', AdminFacultyController::class);
        Route::patch('/faculty/{faculty}/toggle-active', [AdminFacultyController::class, 'toggleActive'])->name('faculty.toggle-active');

        // Department management (Admin has full CRUD)
        Route::resource('departments', AdminDepartmentController::class)->except(['show'])->names([
            'index'   => 'departments.index',
            'create'  => 'departments.create',
            'store'   => 'departments.store',
            'edit'    => 'departments.edit',
            'update'  => 'departments.update',
            'destroy' => 'departments.destroy',
        ]);

        // Grade management (view all, reopen)
        Route::get('/grades', [AdminGradeController::class, 'index'])->name('grades.index');
        Route::patch('/grades/{grade}/reopen', [AdminGradeController::class, 'reopen'])->name('grades.reopen');
        Route::patch('/section-subjects/{sectionSubject}/grades/reopen', [AdminGradeController::class, 'reopenSectionSubject'])->name('grades.reopen-section-subject');
        Route::get('/grades/audit-log', [AdminGradeController::class, 'auditLog'])->name('grades.audit-log');
    });

// ─── Registrar Routes ────────────────────────────────────────────

Route::prefix('registrar')
    ->name('registrar.')
    ->middleware(['auth', 'role:registrar'])
    ->group(function () {
        Route::get('/dashboard', [RegistrarDashboard::class, 'index'])->name('dashboard');

        // Application management (Admission)
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
        Route::patch('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');

        // Grade Level management
        Route::resource('grade-levels', GradeLevelController::class)->except(['show']);

        // Student management
        Route::resource('students', StudentController::class);
        Route::patch('/students/{student}/approve', [StudentController::class, 'approve'])->name('students.approve');
        Route::patch('/students/{student}/reject', [StudentController::class, 'reject'])->name('students.reject');

        // Enrollment management
        Route::resource('enrollments', EnrollmentController::class)->except(['edit', 'update']);
        Route::post('/enrollments/{enrollment}/assess', [EnrollmentController::class, 'assess'])->name('enrollments.assess');
        Route::post('/enrollments/{enrollment}/finalize', [EnrollmentController::class, 'finalize'])->name('enrollments.finalize');

        // Section management
        Route::resource('sections', SectionController::class);

        // Payment management
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::patch('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

        // Tuition Pricing (read-only)
        Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
        Route::get('/pricing/{pricing}', [PricingController::class, 'show'])->name('pricing.show');

        // Grade management (view all, reopen) — shares AdminGradeController
        Route::get('/grades', [\App\Http\Controllers\Admin\GradeController::class, 'index'])->name('grades.index');
        Route::patch('/grades/{grade}/reopen', [\App\Http\Controllers\Admin\GradeController::class, 'reopen'])->name('grades.reopen');
        Route::patch('/section-subjects/{sectionSubject}/grades/reopen', [\App\Http\Controllers\Admin\GradeController::class, 'reopenSectionSubject'])->name('grades.reopen-section-subject');
        Route::get('/grades/audit-log', [\App\Http\Controllers\Admin\GradeController::class, 'auditLog'])->name('grades.audit-log');
    });

// ─── Faculty Routes ──────────────────────────────────────────────

Route::prefix('faculty')
    ->name('faculty.')
    ->middleware(['auth', 'role:faculty'])
    ->group(function () {
        Route::get('/dashboard', [FacultyDashboard::class, 'index'])->name('dashboard');

        // View teaching loads & class lists
        Route::get('/sections', [FacultySectionController::class, 'index'])->name('sections.index');
        Route::get('/sections/{sectionSubject}', [FacultySectionController::class, 'show'])->name('sections.show');

        // Grade management (per section-subject)
        Route::get('/sections/{sectionSubject}/grades', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('/sections/{sectionSubject}/grades', [GradeController::class, 'update'])->name('grades.update');
        Route::post('/sections/{sectionSubject}/grades/finalize', [GradeController::class, 'finalize'])->name('grades.finalize');
        Route::post('/sections/{sectionSubject}/grades/import', [GradeController::class, 'import'])->name('grades.import');
        Route::get('/sections/{sectionSubject}/grades/template', [GradeController::class, 'downloadTemplate'])->name('grades.template');
        Route::get('/sections/{sectionSubject}/grades/audit', [GradeController::class, 'auditLog'])->name('grades.audit');
    });

// ─── Student Routes ──────────────────────────────────────────────

Route::prefix('student')
    ->name('student.')
    ->middleware(['auth', 'role:student'])
    ->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        // View enrollment
        Route::get('/enrollment', [StudentEnrollmentController::class, 'index'])->name('enrollment');
        Route::get('/enrollment/{enrollment}/payments', [StudentEnrollmentController::class, 'payments'])->name('payments');

        // View schedule
        Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

        // View grades
        Route::get('/grades', [StudentGradeController::class, 'index'])->name('grades');
    });
