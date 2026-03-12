<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\ExamSchedule;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    /**
     * Show the public admission application form.
     */
    public function create(): View
    {
        $programs = Program::where('is_active', true)->orderBy('name')->get();

        return view('admission.apply', compact('programs'));
    }

    /**
     * Store a new admission application (public — no auth required).
     */
    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle optional document upload
        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')
                ->store('applications/documents', 'public');
        }

        // Remove the 'document' key (file object) — we store 'document_path' instead
        unset($data['document']);

        Application::create($data);

        return redirect()->route('admission.success');
    }

    /**
     * Show application submitted success page.
     */
    public function success(): View
    {
        return view('admission.success');
    }

    /**
     * Allow applicant to check their application status (by email).
     */
    public function track(): View
    {
        return view('admission.track');
    }

    /**
     * Show the JHS admission application form.
     */
    public function jhsForm(): View
    {
        $jhsPrograms = Program::where('is_active', true)
            ->where('code', 'like', 'JHS-%')
            ->orderByRaw("FIELD(code,'JHS-G7','JHS-G8','JHS-G9','JHS-G10')")
            ->get();

        return view('admission.jhs_admission', compact('jhsPrograms'));
    }

    /**
     * Store a JHS admission application.
     * Saves as a pending Application — student only becomes active
     * after passing the entrance exam and completing payment.
     */
    public function storeJhs(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name'            => ['required', 'string', 'max:255'],
            'middle_name'           => ['nullable', 'string', 'max:255'],
            'last_name'             => ['required', 'string', 'max:255'],
            'lrn'                   => ['nullable', 'string', 'max:12'],
            'email'                 => ['required', 'email', 'max:255', 'unique:applications,email', 'unique:users,email'],
            'contact_number'        => ['nullable', 'regex:/^(09|\+639)\d{9}$/'],
            'date_of_birth'         => ['nullable', 'date', 'before:today'],
            'gender'                => ['nullable', 'in:male,female'],
            'nationality'           => ['nullable', 'string', 'max:100'],
            'religion'              => ['nullable', 'string', 'max:100'],
            'address'               => ['nullable', 'string', 'max:1000'],
            'program_applied_id'    => ['required', 'exists:programs,id'],
            'year_level'            => ['required', 'integer', 'min:7', 'max:10'],
            'guardian_name'         => ['nullable', 'string', 'max:255'],
            'guardian_contact'      => ['nullable', 'regex:/^(09|\+639)\d{9}$/'],
            'guardian_relationship' => ['nullable', 'string', 'max:100'],
            'elementary_school'     => ['nullable', 'string', 'max:255'],
            'document'              => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'email.unique'                    => 'This email already has an existing application or account.',
            'contact_number.regex'            => 'Student contact must be a valid PH mobile number (e.g. 09171234567).',
            'guardian_contact.regex'          => 'Guardian contact must be a valid PH mobile number (e.g. 09171234567).',
        ]);

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')
                ->store('applications/documents', 'public');
        }
        unset($data['document']);

        // Create application and generate unique App ID
        $app = Application::create($data);
        $appId = 'APP-' . date('Y') . '-' . str_pad($app->id, 5, '0', STR_PAD_LEFT);
        $app->update(['app_id' => $appId]);

        return redirect()->route('admission.exam-schedule', $appId);
    }

    /**
     * Show the entrance exam schedule selection page.
     */
    public function examSchedule(string $appId): View
    {
        $application = Application::where('app_id', $appId)
            ->with('program')
            ->firstOrFail();

        $schedules = ExamSchedule::withSlots()->get();

        return view('admission.exam_schedule', compact('application', 'schedules'));
    }

    /**
     * Save the student's chosen exam schedule.
     */
    public function storeExamSchedule(\Illuminate\Http\Request $request, string $appId): RedirectResponse
    {
        $application = Application::where('app_id', $appId)->firstOrFail();

        $request->validate([
            'exam_schedule_id' => ['required', 'exists:exam_schedules,id'],
        ], [
            'exam_schedule_id.required' => 'Please choose an exam schedule before continuing.',
            'exam_schedule_id.exists'   => 'The selected schedule is no longer available.',
        ]);

        $sched = ExamSchedule::withCount('applications')->findOrFail($request->exam_schedule_id);

        if (! $sched->is_active) {
            return back()->withErrors(['exam_schedule_id' => 'That schedule is no longer available.'])->withInput();
        }

        $available = max(0, $sched->max_capacity - $sched->applications_count);
        if ($available === 0) {
            return back()->withErrors(['exam_schedule_id' => 'That schedule is now full. Please choose another.'])->withInput();
        }

        $application->update(['exam_schedule_id' => $sched->id]);

        return redirect()->route('admission.payment.show', $appId);
    }

    /**
     * Look up application status by Application ID or email.
     */
    public function trackSearch(\Illuminate\Http\Request $request): View
    {
        $request->validate(['search' => 'required|string|max:255']);

        $search = trim($request->search);

        $application = Application::with(['program', 'admissionPayment'])
            ->where('app_id', $search)
            ->orWhere('email', $search)
            ->latest()
            ->first();

        return view('admission.track', compact('application'));
    }
}
