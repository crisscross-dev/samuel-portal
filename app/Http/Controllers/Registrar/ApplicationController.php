<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ExamSchedule;
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

    /**
     * List all applications with filtering.
     */
    public function index(Request $request): View
    {
        $query = Application::with('program', 'reviewer', 'admissionPayment');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $stats = [
            'pending'  => Application::pending()->count(),
            'approved' => Application::approved()->count(),
            'rejected' => Application::rejected()->count(),
            'total'    => Application::count(),
        ];

        return view('registrar.applications.index', compact('applications', 'programs', 'stats'));
    }

    /**
     * Show a single application with full details.
     */
    public function show(Application $application): View
    {
        $application->load('program', 'reviewer', 'admissionPayment.verifier', 'examSchedule');

        $activeSchedules = ExamSchedule::active()->withCount('applications')->orderBy('exam_date')->orderBy('time_slot')->get();

        return view('registrar.applications.show', compact('application', 'activeSchedules'));
    }

    /**
     * Verify the GCash payment for an application.
     */
    public function verifyPayment(Request $request, Application $application): RedirectResponse
    {
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

        return back()->with('success', 'Payment verified successfully. You may now approve the application.');
    }

    /**
     * Assign or change the exam schedule for an application.
     */
    public function assignSchedule(Request $request, Application $application): RedirectResponse
    {
        $request->validate([
            'exam_schedule_id' => ['nullable', 'exists:exam_schedules,id'],
        ]);

        $application->update(['exam_schedule_id' => $request->exam_schedule_id ?: null]);

        return back()->with('success', 'Exam schedule updated.');
    }

    /**
     * Approve an application — creates user account + student profile.
     */
    public function approve(Request $request, Application $application): RedirectResponse
    {
        try {
            $student = $this->admissionService->approveApplication(
                $application,
                Auth::id(),
                $request->input('remarks')
            );

            return redirect()->route('registrar.applications.index')
                ->with('success', "Application approved. Student account created — ID: {$student->student_id}, Default password: password");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject an application — no account created.
     */
    public function reject(Request $request, Application $application): RedirectResponse
    {
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
}
