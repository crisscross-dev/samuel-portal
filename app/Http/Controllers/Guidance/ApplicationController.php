<?php

namespace App\Http\Controllers\Guidance;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\AdmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function __construct(
        protected AdmissionService $admissionService,
    ) {}

    public function index(Request $request): View
    {
        $query = Application::with('program', 'guidanceUser', 'interviewSlot')
            ->where('workflow_stage', Application::WORKFLOW_GUIDANCE_REVIEW);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest('forwarded_to_guidance_at')->paginate(15)->withQueryString();

        return view('guidance.applications.index', compact('applications'));
    }

    public function results(Request $request): View
    {
        $query = Application::with('program', 'guidanceUser', 'interviewEvaluator', 'interviewSlot')
            ->where('workflow_stage', Application::WORKFLOW_INTERVIEW_FORM_SUBMITTED)
            ->whereNull('interview_result');

        if ($request->filled('workflow_stage')) {
            $query->where('workflow_stage', $request->workflow_stage);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest('interview_evaluated_at')->paginate(15)->withQueryString();

        return view('guidance.applications.results', compact('applications'));
    }

    public function logs(Request $request): View
    {
        $query = Application::with('program', 'guidanceUser', 'interviewEvaluator', 'interviewSlot')
            ->whereNotNull('interview_result')
            ->whereIn('workflow_stage', [
                Application::WORKFLOW_REGISTRAR_REQUIREMENTS,
                Application::WORKFLOW_ENROLLMENT,
                Application::WORKFLOW_CASHIER_PAYMENT,
                Application::WORKFLOW_ARCHIVED,
            ]);

        if ($request->filled('interview_result')) {
            $result = $request->string('interview_result')->toString();
            if (in_array($result, [
                Application::INTERVIEW_RESULT_PASSED,
                Application::INTERVIEW_RESULT_CONSIDERED,
                Application::INTERVIEW_RESULT_FAILED,
            ], true)) {
                $query->where('interview_result', $result);
            }
        }

        if ($request->filled('workflow_stage')) {
            $query->where('workflow_stage', $request->workflow_stage);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest('interview_evaluated_at')->paginate(15)->withQueryString();

        return view('guidance.applications.logs', compact('applications'));
    }

    public function show(Application $application): View
    {
        abort_unless(in_array($application->workflow_stage, [
            Application::WORKFLOW_GUIDANCE_REVIEW,
            Application::WORKFLOW_INTERVIEW_SCHEDULED,
            Application::WORKFLOW_INTERVIEW_FORM_SUBMITTED,
            Application::WORKFLOW_REGISTRAR_REQUIREMENTS,
            Application::WORKFLOW_ENROLLMENT,
            Application::WORKFLOW_CASHIER_PAYMENT,
            Application::WORKFLOW_ARCHIVED,
        ], true), 404);

        $application->load('program', 'reviewer', 'examReviewer', 'guidanceUser', 'interviewEvaluator', 'examSchedule', 'interviewSlot');

        return view('guidance.applications.show', compact('application'));
    }

    public function scheduleInterview(Request $request, Application $application): RedirectResponse
    {
        $request->validate([
            'guidance_remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $this->admissionService->scheduleInterview(
                $application,
                Auth::id(),
                $request->input('guidance_remarks')
            );

            return redirect()
                ->route('guidance.applications.index')
                ->with('success', 'Form link sent. Applicant remains in queue until they submit the form.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function evaluateInterview(Request $request, Application $application): RedirectResponse
    {
        $request->validate([
            'interview_result' => ['required', 'in:passed,failed,considered'],
            'interview_remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $updated = $this->admissionService->recordInterviewResult(
                $application,
                Auth::id(),
                $request->string('interview_result')->toString(),
                $request->input('interview_remarks')
            );

            $message = match ($updated->interview_result) {
                Application::INTERVIEW_RESULT_PASSED => 'Applicant passed the interview and was returned to the Registrar for requirements verification.',
                Application::INTERVIEW_RESULT_CONSIDERED => 'Applicant was marked as considered and was returned to the Registrar for continued processing.',
                default => 'Applicant failed the interview and the record has been archived.',
            };

            return redirect()
                ->route('guidance.applications.logs')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
