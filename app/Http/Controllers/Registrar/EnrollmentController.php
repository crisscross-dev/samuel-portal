<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registrar\StoreEnrollmentRequest;
use App\Models\Application;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Services\AdmissionService;
use App\Services\AssessmentService;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(
        private EnrollmentService $enrollmentService,
        private AssessmentService $assessmentService,
        private AdmissionService $admissionService,
    ) {}

    public function index(Request $request): View
    {
        $enrollmentCandidates = Application::with('program')
            ->where('workflow_stage', Application::WORKFLOW_ENROLLMENT)
            ->latest('requirements_verified_at')
            ->get();

        return view('registrar.enrollments.index', compact('enrollmentCandidates'));
    }

    public function create(Request $request): View
    {
        $application = null;
        $selectedStudent = null;

        if ($request->filled('application_id')) {
            $application = Application::with('program')
                ->findOrFail($request->integer('application_id'));

            if (!$application->isInEnrollmentStage()) {
                abort(404);
            }

            $selectedStudent = Student::whereHas('user', fn($query) => $query->where('email', $application->email))
                ->with('user', 'program')
                ->first();
        }

        $students = Student::with('user')
            ->enrollable()
            ->get();
        $semester = Semester::current();

        // Get sections for the active academic year with available capacity
        $activeAY = \App\Models\AcademicYear::where('is_active', true)->first();
        $sections = $activeAY
            ? Section::with(['gradeLevel.department', 'adviser.user'])
            ->where('academic_year_id', $activeAY->id)
            ->withCount('students')
            ->get()
            : collect();

        return view('registrar.enrollments.create', compact('students', 'semester', 'sections', 'application', 'selectedStudent'));
    }

    public function store(StoreEnrollmentRequest $request): RedirectResponse
    {
        try {
            $studentId = (int) $request->student_id;

            if ($request->filled('application_id')) {
                $application = Application::with('program')->findOrFail($request->integer('application_id'));
                $student = $this->admissionService->ensureStudentForEnrollment($application);
                $studentId = $student->id;
            }

            $enrollment = $this->enrollmentService->createEnrollment(
                $studentId,
                $request->semester_id,
                $request->section_id
            );

            if ($request->filled('application_id')) {
                $application = Application::findOrFail($request->integer('application_id'));
                $this->admissionService->processEnrollment(
                    $application,
                    $request->user()->id,
                    'Enrollment created via registrar enrollment module.'
                );
            }

            return redirect()->route('registrar.enrollments.show', $enrollment)
                ->with('success', 'Enrollment created successfully. The applicant has been forwarded to Cashier for payment.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Enrollment $enrollment): View
    {
        $enrollment->load([
            'student.user',
            'student.program',
            'student.section.gradeLevel.department',
            'semester.academicYear',
            'enrollmentSubjects.subject',
            'enrollmentSubjects.section',
            'payments.verifier',
        ]);

        $breakdown = $enrollment->total_amount > 0
            ? $this->assessmentService->getBreakdown($enrollment)
            : null;

        return view('registrar.enrollments.show', compact('enrollment', 'breakdown'));
    }

    /**
     * Generate tuition assessment for an enrollment.
     */
    public function assess(Enrollment $enrollment): RedirectResponse
    {
        try {
            $this->assessmentService->generateAssessment($enrollment);
            return back()->with('success', 'Assessment generated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Finalize enrollment after payment verification.
     */
    public function finalize(Enrollment $enrollment): RedirectResponse
    {
        try {
            $this->enrollmentService->finalizeEnrollment($enrollment);
            return back()->with('success', 'Enrollment finalized successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        if ($enrollment->status === 'enrolled') {
            return back()->with('error', 'Cannot delete a finalized enrollment.');
        }

        $enrollment->enrollmentSubjects()->delete();
        $enrollment->delete();

        return redirect()->route('registrar.enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }
}
