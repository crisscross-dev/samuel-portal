<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AdmissionPaymentController;
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
use App\Http\Controllers\Guidance\ApplicationController as GuidanceApplicationController;
use App\Http\Controllers\Guidance\DashboardController as GuidanceDashboard;
use App\Http\Controllers\Guidance\SchedulerController as GuidanceScheduler;
use App\Http\Controllers\Registrar\ApplicationController;
use App\Http\Controllers\Registrar\DashboardController as RegistrarDashboard;
use App\Http\Controllers\Registrar\EnrollmentController;
use App\Http\Controllers\Registrar\ExamScheduleController;
use App\Http\Controllers\Registrar\GradeLevelController;
use App\Http\Controllers\Registrar\PaymentController;
use App\Http\Controllers\Registrar\SectionController;
use App\Http\Controllers\Registrar\StudentAccountController;
use App\Http\Controllers\Registrar\StudentController;
use App\Http\Controllers\Registrar\SubjectController;
use App\Http\Controllers\SidebarBadgeController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollmentController;
use App\Http\Controllers\Student\GradeController as StudentGradeController;
use App\Http\Controllers\Student\ScheduleController;
use Illuminate\Support\Facades\Route;

// ─── Public / Auth ────────────────────────────────────────────────

Route::get('/', fn() => view('welcome'))->name('home');

// ─── Landing Pages ────────────────────────────────────────────────

Route::get('/junior-high-school', fn() => view('landingpage.jhs'))->name('landing.jhs');
Route::get('/senior-high-school', fn() => view('landingpage.shs'))->name('landing.shs');
Route::get('/college', fn() => view('landingpage.college'))->name('landing.college');

Route::get('/school-admin', fn() => view('landingpage.school_admin'))->name('landing.school_admin');
Route::get('/aass-admin', fn() => view('landingpage.aass_admin'))->name('landing.aass_admin');
Route::get('/sas-admin', fn() => view('landingpage.sas_admin'))->name('landing.sas_admin');
Route::get('/hed-admin', fn() => view('landingpage.hed_admin'))->name('landing.hed_admin');
Route::get('/bed-admin', fn() => view('landingpage.bed_admin'))->name('landing.bed_admin');

Route::get('/school-profile', fn() => view('landingpage.about_us.school_profile'))->name('landing.school_profile');
Route::get('/vision-mission', fn() => view('landingpage.about_us.vision_mission'))->name('landing.vision_mission');
Route::get('/core-values-goals', fn() => view('landingpage.about_us.core_values_goals'))->name('landing.core_values_goals');
Route::get('/educational-philosophy', fn() => view('landingpage.about_us.educational_philosophy'))->name('landing.educational_philosophy');
Route::get('/clubs', fn() => view('landingpage.about_us.clubs'))->name('landing.clubs');
Route::get('/virtual-tour', fn() => view('landingpage.about_us.virtual_tour'))->name('landing.virtual_tour');

Route::get('/contact-us', fn() => view('landingpage.about_us.contact_us'))->name('landing.contact_us');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/sidebar/badges', SidebarBadgeController::class)
    ->middleware('auth')
    ->name('sidebar.badges');

// ─── Public Admission ─────────────────────────────────────────────

Route::prefix('admission')->name('admission.')->group(function () {
    Route::get('/apply',   [AdmissionController::class, 'create'])->name('apply');
    Route::post('/apply',  [AdmissionController::class, 'store'])->name('store');
    Route::get('/jhs',     [AdmissionController::class, 'jhsForm'])->name('jhs');
    Route::post('/jhs',    [AdmissionController::class, 'storeJhs'])->name('jhs.store');
    Route::get('/shs',     [AdmissionController::class, 'shsForm'])->name('shs');
    Route::post('/shs',    [AdmissionController::class, 'storeShs'])->name('shs.store');
    Route::get('/success', [AdmissionController::class, 'success'])->name('success');
    Route::get('/track',   [AdmissionController::class, 'track'])->name('track');
    Route::post('/track',  [AdmissionController::class, 'trackSearch'])->name('track.search');
    Route::get('/exam-schedule/{appId}',  [AdmissionController::class, 'examSchedule'])->name('exam-schedule');
    Route::post('/exam-schedule/{appId}', [AdmissionController::class, 'storeExamSchedule'])->name('exam-schedule.store');
    Route::get('/payment/{appId}',  [AdmissionPaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{appId}', [AdmissionPaymentController::class, 'store'])->name('payment.store');
    Route::get('/interview-form/{token}', [AdmissionController::class, 'showInterviewForm'])->name('interview-form.show');
    Route::post('/interview-form/{token}', [AdmissionController::class, 'submitInterviewForm'])->name('interview-form.submit');
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
    ->middleware(['auth', 'role:registrar,jhs-registrar,shs-registrar'])
    ->group(function () {
        Route::get('/dashboard', [RegistrarDashboard::class, 'index'])->name('dashboard');

        // Application management (Admission)
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/{application}/receipt-image', [ApplicationController::class, 'receiptImage'])->name('applications.receipt-image');
        Route::patch('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
        Route::patch('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
        Route::patch('/applications/{application}/exam-result', [ApplicationController::class, 'recordExamResult'])->name('applications.exam-result');
        Route::patch('/applications/{application}/verify-requirements', [ApplicationController::class, 'verifyRequirements'])->name('applications.verify-requirements');
        Route::patch('/applications/{application}/process-enrollment', [ApplicationController::class, 'processEnrollment'])->name('applications.process-enrollment');
        Route::patch('/applications/{application}/verify-payment', [ApplicationController::class, 'verifyPayment'])->name('applications.verify-payment');
        Route::patch('/applications/{application}/assign-schedule', [ApplicationController::class, 'assignSchedule'])->name('applications.assign-schedule');

        // Exam Schedule management
        Route::get('/exam-schedules', [ExamScheduleController::class, 'index'])->name('exam-schedules.index');
        Route::post('/exam-schedules', [ExamScheduleController::class, 'store'])->name('exam-schedules.store');
        Route::patch('/exam-schedules/{examSchedule}', [ExamScheduleController::class, 'update'])->name('exam-schedules.update');
        Route::patch('/exam-schedules/{examSchedule}/toggle', [ExamScheduleController::class, 'toggle'])->name('exam-schedules.toggle');
        Route::delete('/exam-schedules/{examSchedule}', [ExamScheduleController::class, 'destroy'])->name('exam-schedules.destroy');

        // Grade Level management
        Route::resource('grade-levels', GradeLevelController::class)->except(['show']);

        // Subject management
        Route::resource('subjects', SubjectController::class)->except(['show']);

        // Student management
        Route::resource('students', StudentController::class);
        Route::patch('/students/{student}/approve', [StudentController::class, 'approve'])->name('students.approve');
        Route::patch('/students/{student}/reject', [StudentController::class, 'reject'])->name('students.reject');

        // Enrollment management
        Route::resource('enrollments', EnrollmentController::class)->except(['edit', 'update']);
        Route::post('/enrollments/{enrollment}/finalize', [EnrollmentController::class, 'finalize'])->name('enrollments.finalize');

        // Student account release management
        Route::get('/student-accounts', [StudentAccountController::class, 'index'])->name('student-accounts.index');
        Route::patch('/student-accounts/{application}/release', [StudentAccountController::class, 'release'])->name('student-accounts.release');

        // Payment logs
        Route::get('/payments/logs', [PaymentController::class, 'logs'])->name('payments.logs');

        // Section management
        Route::resource('sections', SectionController::class);

        // Tuition Pricing (read-only)
        Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
        Route::get('/pricing/{pricing}', [PricingController::class, 'show'])->name('pricing.show');

        // Grade management (view all, reopen) — shares AdminGradeController
        Route::get('/grades', [\App\Http\Controllers\Admin\GradeController::class, 'index'])->name('grades.index');
        Route::patch('/grades/{grade}/reopen', [\App\Http\Controllers\Admin\GradeController::class, 'reopen'])->name('grades.reopen');
        Route::patch('/section-subjects/{sectionSubject}/grades/reopen', [\App\Http\Controllers\Admin\GradeController::class, 'reopenSectionSubject'])->name('grades.reopen-section-subject');
        Route::get('/grades/audit-log', [\App\Http\Controllers\Admin\GradeController::class, 'auditLog'])->name('grades.audit-log');
    });

// ─── Guidance Routes ─────────────────────────────────────────────

Route::prefix('guidance')
    ->name('guidance.')
    ->middleware(['auth', 'role:guidance'])
    ->group(function () {
        Route::get('/dashboard', [GuidanceDashboard::class, 'index'])->name('dashboard');
        Route::get('/scheduler', [GuidanceScheduler::class, 'index'])->name('scheduler.index');
        Route::get('/scheduler/logs', [GuidanceScheduler::class, 'logs'])->name('scheduler.logs');
        Route::post('/scheduler/interview-slots', [GuidanceScheduler::class, 'store'])->name('interview-slots.store');
        Route::patch('/scheduler/interview-slots/{slot}/toggle', [GuidanceScheduler::class, 'toggle'])->name('interview-slots.toggle');
        Route::delete('/scheduler/interview-slots/{slot}', [GuidanceScheduler::class, 'destroy'])->name('interview-slots.destroy');
        Route::get('/applications', [GuidanceApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/results', [GuidanceApplicationController::class, 'results'])->name('applications.results');
        Route::get('/applications/logs', [GuidanceApplicationController::class, 'logs'])->name('applications.logs');
        Route::get('/applications/{application}', [GuidanceApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{application}/schedule-interview', [GuidanceApplicationController::class, 'scheduleInterview'])->name('applications.schedule-interview');
        Route::patch('/applications/{application}/evaluate-interview', [GuidanceApplicationController::class, 'evaluateInterview'])->name('applications.evaluate-interview');
    });

// ─── Cashier Routes ──────────────────────────────────────────────

Route::prefix('cashier')
    ->name('cashier.')
    ->middleware(['auth', 'role:cashier'])
    ->group(function () {
        Route::get('/dashboard', fn() => redirect()->route('cashier.payments.index'))->name('dashboard');
        Route::get('/payments/logs', [PaymentController::class, 'logs'])->name('payments.logs');
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::patch('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
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
