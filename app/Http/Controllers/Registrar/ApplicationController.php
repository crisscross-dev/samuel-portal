<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ExamSchedule;
use App\Services\AdmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApplicationController extends Controller
{
    public function __construct(
        protected AdmissionService $admissionService,
    ) {}

    /**
     * List all applications with filtering.
     */
    public function index(Request $request): View
    {
        $query = Application::with('program', 'reviewer', 'examReviewer', 'guidanceUser', 'requirementsVerifier', 'enrollmentProcessor', 'admissionPayment');
        $stageGroup = $request->string('stage_group')->toString();
        $programPrefix = $this->programPrefixForCurrentRegistrar($request->user());

        if ($programPrefix !== null) {
            $query->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'));
        }

        if ($stageGroup !== '') {
            match ($stageGroup) {
                'admission' => $query->where('workflow_stage', Application::WORKFLOW_SUBMITTED),
                'exam' => $query->whereIn('workflow_stage', [
                    Application::WORKFLOW_EXAM_APPROVED,
                    Application::WORKFLOW_EXAM_FAILED,
                ]),
                'requirements' => $query->whereIn('workflow_stage', [
                    Application::WORKFLOW_REGISTRAR_REQUIREMENTS,
                    Application::WORKFLOW_ENROLLMENT,
                    Application::WORKFLOW_CASHIER_PAYMENT,
                ]),
                default => null,
            };
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('workflow_stage')) {
            $query->where('workflow_stage', $request->workflow_stage);
        }

        if ($request->filled('program_id')) {
            $query->where('program_applied_id', $request->program_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        $programs = \App\Models\Program::where('is_active', true)->get();

        $statsBase = Application::query();
        if ($programPrefix !== null) {
            $statsBase->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'));
        }

        $stats = [
            'pending'  => (clone $statsBase)->pending()->count(),
            'exam_approved' => (clone $statsBase)->workflowStage(Application::WORKFLOW_EXAM_APPROVED)->count(),
            'guidance_review' => (clone $statsBase)->workflowStage(Application::WORKFLOW_GUIDANCE_REVIEW)->count(),
            'registrar_requirements' => (clone $statsBase)->workflowStage(Application::WORKFLOW_REGISTRAR_REQUIREMENTS)->count(),
            'enrollment' => (clone $statsBase)->workflowStage(Application::WORKFLOW_ENROLLMENT)->count(),
            'cashier' => (clone $statsBase)->workflowStage(Application::WORKFLOW_CASHIER_PAYMENT)->count(),
            'inactive' => (clone $statsBase)->where('is_active', false)->count(),
            'total' => (clone $statsBase)->count(),
        ];

        $stageGroupMeta = [
            'admission' => [
                'title' => 'Admission Queue',
                'description' => 'Verify student payment submissions and review applications before clearing applicants for the entrance exam.',
            ],
            'exam' => [
                'title' => 'Exam Queue',
                'description' => 'Manage students approved for the entrance exam and record pass or fail results before they move to the next office.',
            ],
            'requirements' => [
                'title' => 'Requirements Queue',
                'description' => 'Review interview passers, verify documentary requirements, process enrollment, and forward records to Cashier.',
            ],
            'all' => [
                'title' => 'Admission Applications',
                'description' => 'Track every registrar-side admission workflow stage in one place.',
            ],
        ];

        $selectedStageGroup = array_key_exists($stageGroup, $stageGroupMeta) ? $stageGroup : 'all';

        return view('registrar.applications.index', compact('applications', 'programs', 'stats', 'selectedStageGroup', 'stageGroupMeta'));
    }

    /**
     * Show a single application with full details.
     */
    public function show(Application $application): View
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        $application->load('program', 'reviewer', 'examReviewer', 'guidanceUser', 'interviewEvaluator', 'requirementsVerifier', 'enrollmentProcessor', 'admissionPayment.verifier', 'examSchedule');

        $examType = $this->resolveExamTypeForApplication($application);

        $activeSchedules = ExamSchedule::active()
            ->forType($examType)
            ->withCount('applications')
            ->orderBy('exam_date')
            ->orderBy('time_slot')
            ->get();

        return view('registrar.applications.show', compact('application', 'activeSchedules'));
    }

    /**
     * Serve receipt image through registrar-authenticated route.
     */
    public function receiptImage(Application $application): BinaryFileResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        $payment = $application->admissionPayment;

        abort_if(!$payment || !$payment->receipt_image, 404);

        $relativePath = ltrim($payment->receipt_image, '/');

        if (Storage::disk('local')->exists($relativePath)) {
            return response()->file(Storage::disk('local')->path($relativePath));
        }

        // Backward compatibility for older uploads saved in public disk.
        if (Storage::disk('public')->exists($relativePath)) {
            return response()->file(Storage::disk('public')->path($relativePath));
        }

        abort(404);
    }

    /**
     * Verify the GCash payment for an application.
     */
    public function verifyPayment(Request $request, Application $application): RedirectResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        $payment = $application->admissionPayment;

        if (!$payment) {
            return back()->with('error', 'No payment record found for this application.');
        }

        if ($payment->payment_status === 'paid') {
            return back()->with('error', 'Payment has already been verified.');
        }

        $payment->update([
            'payment_status' => 'paid',
            'verified_by'    => Auth::id(),
            'verified_at'    => now(),
        ]);

        $application->update(['payment_status' => 'paid']);

        return back()->with('success', 'Payment verified successfully. You may now clear the applicant for the entrance exam.');
    }

    /**
     * Assign or change the exam schedule for an application.
     */
    public function assignSchedule(Request $request, Application $application): RedirectResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        $request->validate([
            'exam_schedule_id' => ['nullable', 'exists:exam_schedules,id'],
        ]);

        if ($request->filled('exam_schedule_id')) {
            $schedule = ExamSchedule::findOrFail($request->exam_schedule_id);
            $examType = $this->resolveExamTypeForApplication($application);

            if ($schedule->exam_type !== $examType) {
                return back()->with('error', 'Selected exam schedule does not match applicant department (JHS/SHS).');
            }
        }

        $application->update(['exam_schedule_id' => $request->exam_schedule_id ?: null]);

        return back()->with('success', 'Exam schedule updated.');
    }

    private function resolveExamTypeForApplication(Application $application): string
    {
        $code = strtoupper((string) optional($application->program)->code);

        return str_starts_with($code, 'SHS-')
            ? ExamSchedule::TYPE_SHS
            : ExamSchedule::TYPE_JHS;
    }

    /**
     * Approve an application for the entrance examination.
     */
    public function approve(Request $request, Application $application): RedirectResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        try {
            $this->admissionService->approveForExam(
                $application,
                Auth::id(),
                $request->input('remarks')
            );

            return redirect()->route('registrar.applications.index')
                ->with('success', 'Application approved for the entrance exam and the applicant has been emailed. No student account has been created yet.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Record the entrance exam result.
     */
    public function recordExamResult(Request $request, Application $application): RedirectResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        $request->validate([
            'exam_result' => ['required', 'in:passed,failed'],
            'exam_remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $updated = $this->admissionService->recordExamResult(
                $application,
                Auth::id(),
                $request->string('exam_result')->toString(),
                $request->input('exam_remarks')
            );

            $message = $updated->exam_result === Application::EXAM_RESULT_PASSED
                ? 'Applicant passed the exam and has been forwarded to the Guidance Office.'
                : 'Applicant failed the entrance exam and the record was moved to the archive.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function verifyRequirements(Request $request, Application $application): RedirectResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        $requirements = [
            'pre_enrolment_form_submitted' => $request->boolean('pre_enrolment_form_submitted'),
            'student_health_form_submitted' => $request->boolean('student_health_form_submitted'),
            'report_card_submitted' => $request->boolean('report_card_submitted'),
            'id_picture_submitted' => $request->boolean('id_picture_submitted'),
        ];

        $request->validate([
            'requirements_remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $updated = $this->admissionService->verifyRequirements(
                $application,
                Auth::id(),
                $requirements,
                $request->input('requirements_remarks')
            );

            $message = $updated->isInEnrollmentStage()
                ? 'Requirements verified. The applicant is now in the enrollment stage.'
                : 'Requirements checklist saved. Complete all required documents to move the applicant to enrollment.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function processEnrollment(Request $request, Application $application): RedirectResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        $request->validate([
            'enrollment_remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $this->admissionService->processEnrollment(
                $application,
                Auth::id(),
                $request->input('enrollment_remarks')
            );

            return back()->with('success', 'Enrollment processed. The applicant has been forwarded to Cashier for payment.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject an application — no account created.
     */
    public function reject(Request $request, Application $application): RedirectResponse
    {
        $this->ensureApplicationWithinRegistrarScope($application);

        try {
            $this->admissionService->rejectApplication(
                $application,
                Auth::id(),
                $request->input('remarks')
            );

            return redirect()->route('registrar.applications.index')
                ->with('success', 'Application has been rejected.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function programPrefixForCurrentRegistrar($user): ?string
    {
        if (!$user) {
            return null;
        }

        if ($user->hasRole('jhs-registrar')) {
            return 'JHS-';
        }

        if ($user->hasRole('shs-registrar')) {
            return 'SHS-';
        }

        return null;
    }

    private function ensureApplicationWithinRegistrarScope(Application $application): void
    {
        $user = request()->user();
        $programPrefix = $this->programPrefixForCurrentRegistrar($user);

        if ($programPrefix === null) {
            return;
        }

        $programCode = strtoupper((string) optional($application->program)->code);

        abort_unless(str_starts_with($programCode, $programPrefix), 403, 'Unauthorized for this registrar department scope.');
    }
}
