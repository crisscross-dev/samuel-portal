<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\ExamSchedule;
use App\Models\Program;
use App\Services\AdmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    private const SHS_ELECTIVE_PROGRAMS = [
        ['code' => 'SHS-ENGR', 'name' => 'Engineering'],
        ['code' => 'SHS-MED', 'name' => 'Medicine'],
        ['code' => 'SHS-BUS', 'name' => 'Business'],
        ['code' => 'SHS-HUM', 'name' => 'Humanities'],
        ['code' => 'SHS-CS', 'name' => 'Computer Studies'],
    ];

    public function __construct(
        protected AdmissionService $admissionService,
    ) {}

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
     * Show the SHS admission application form.
     */
    public function shsForm(): View
    {
        $this->ensureShsElectivePrograms();

        $shsPrograms = Program::where('is_active', true)
            ->whereIn('code', collect(self::SHS_ELECTIVE_PROGRAMS)->pluck('code'))
            ->orderByRaw("FIELD(code,'SHS-ENGR','SHS-MED','SHS-BUS','SHS-HUM','SHS-CS')")
            ->get();

        return view('admission.shs_admission', compact('shsPrograms'));
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
     * Store an SHS admission application.
     * Uses the same application table/workflow as JHS for consistency.
     */
    public function storeShs(\Illuminate\Http\Request $request): RedirectResponse
    {
        $this->ensureShsElectivePrograms();

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
            'year_level'            => ['required', 'integer', 'in:11,12'],
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

        $app = Application::create($data);
        $appId = 'APP-' . date('Y') . '-' . str_pad($app->id, 5, '0', STR_PAD_LEFT);
        $app->update(['app_id' => $appId]);

        return redirect()->route('admission.exam-schedule', $appId);
    }

    private function ensureShsElectivePrograms(): void
    {
        foreach (self::SHS_ELECTIVE_PROGRAMS as $program) {
            Program::updateOrCreate(
                ['code' => $program['code']],
                [
                    'name' => $program['name'],
                    'duration_years' => 2,
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Show the entrance exam schedule selection page.
     */
    public function examSchedule(string $appId): View
    {
        $application = Application::where('app_id', $appId)
            ->with('program')
            ->firstOrFail();

        $examType = $this->resolveExamTypeForApplication($application);

        $schedules = ExamSchedule::forType($examType)->withSlots()->get();

        return view('admission.exam_schedule', compact('application', 'schedules'));
    }

    /**
     * Save the student's chosen exam schedule.
     */
    public function storeExamSchedule(\Illuminate\Http\Request $request, string $appId): RedirectResponse
    {
        $application = Application::where('app_id', $appId)
            ->with('program')
            ->firstOrFail();

        $examType = $this->resolveExamTypeForApplication($application);

        $request->validate([
            'exam_schedule_id' => ['required', 'exists:exam_schedules,id'],
        ], [
            'exam_schedule_id.required' => 'Please choose an exam schedule before continuing.',
            'exam_schedule_id.exists'   => 'The selected schedule is no longer available.',
        ]);

        $sched = ExamSchedule::withCount('applications')->findOrFail($request->exam_schedule_id);

        if ($sched->exam_type !== $examType) {
            return back()->withErrors(['exam_schedule_id' => 'The selected schedule is not available for your department.'])->withInput();
        }

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

    private function resolveExamTypeForApplication(Application $application): string
    {
        $code = strtoupper((string) optional($application->program)->code);

        return str_starts_with($code, 'SHS-')
            ? ExamSchedule::TYPE_SHS
            : ExamSchedule::TYPE_JHS;
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

    /**
     * Show the guidance interview follow-up form.
     */
    public function showInterviewForm(string $token): View
    {
        $application = Application::with('program')
            ->where('interview_form_token', $token)
            ->firstOrFail();

        $formType = $this->resolveInterviewFormType($application);

        return view('admission.interview_form', compact('application', 'formType'));
    }

    /**
     * Submit the guidance interview follow-up form.
     */
    public function submitInterviewForm(Request $request, string $token): RedirectResponse
    {
        $application = Application::with('program')
            ->where('interview_form_token', $token)
            ->firstOrFail();

        $formType = $this->resolveInterviewFormType($application);
        $isShs = $formType === 'shs';
        $isGrade12 = (int) $application->year_level === 12;

        $validated = $request->validate([
            'middle_name' => ['nullable', 'string', 'max:255'],
            'lrn' => ['nullable', 'string', 'max:12'],
            'contact_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'nationality' => ['required', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_contact' => ['required', 'string', 'max:20'],
            'guardian_relationship' => ['required', 'string', 'max:100'],
            'elementary_school' => ['required', 'string', 'max:255'],
            'date_of_enrollment' => ['required', 'date'],
            'student_classification' => ['required', 'string', 'max:100'],
            'extension_name' => ['nullable', 'string', 'max:100'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'father_contact' => ['required', 'string', 'max:20'],
            'mother_name' => ['required', 'string', 'max:255'],
            'mother_contact' => ['required', 'string', 'max:20'],
            'type_of_subsidy' => ['nullable', 'string', 'max:100'],
            'previous_school_classification' => $isShs ? ['nullable', 'string', 'max:100'] : ['required', 'string', 'max:100'],
            'esc_grantee' => $isShs ? ['nullable', 'string', 'max:30'] : ['required', 'in:yes,no,not_applicable'],
            'preferred_interview_date' => ['nullable', 'date'],
            'preferred_interview_time' => ['nullable', 'string', 'max:50'],
            'elective_course' => $isShs && !$isGrade12 ? ['required', 'string', 'max:255'] : ['nullable', 'string', 'max:255'],
            'strand' => $isShs && $isGrade12 ? ['required', 'string', 'max:100'] : ['nullable', 'string', 'max:100'],
            'last_year_section' => $isShs && $isGrade12 ? ['required', 'string', 'max:100'] : ['nullable', 'string', 'max:100'],
        ]);

        $payload = [
            'form_type' => $formType,
            'incoming_grade_level' => (int) $application->year_level,
            'date_of_enrollment' => $validated['date_of_enrollment'],
            'student_classification' => $validated['student_classification'],
            'extension_name' => $validated['extension_name'] ?? null,
            'place_of_birth' => $validated['place_of_birth'],
            'father_name' => $validated['father_name'],
            'father_contact' => $validated['father_contact'],
            'mother_name' => $validated['mother_name'],
            'mother_contact' => $validated['mother_contact'],
            'type_of_subsidy' => $validated['type_of_subsidy'] ?? null,
            'previous_school_classification' => $validated['previous_school_classification'] ?? null,
            'esc_grantee' => $validated['esc_grantee'] ?? null,
            'preferred_interview_date' => $validated['preferred_interview_date'] ?? null,
            'preferred_interview_time' => $validated['preferred_interview_time'] ?? null,
            'elective_course' => $validated['elective_course'] ?? null,
            'strand' => $validated['strand'] ?? null,
            'last_year_section' => $validated['last_year_section'] ?? null,
        ];

        $updateData = [
            'middle_name' => $validated['middle_name'] ?? null,
            'lrn' => $validated['lrn'] ?? null,
            'contact_number' => $validated['contact_number'],
            'address' => $validated['address'],
            'nationality' => $validated['nationality'],
            'religion' => $validated['religion'] ?? null,
            'guardian_name' => $validated['guardian_name'],
            'guardian_contact' => $validated['guardian_contact'],
            'guardian_relationship' => $validated['guardian_relationship'],
            'elementary_school' => $validated['elementary_school'],
            'interview_form_data' => $payload,
        ];

        $this->admissionService->submitInterviewForm($application, $updateData);

        return back()->with('success', 'Your interview form has been submitted successfully.');
    }

    private function resolveInterviewFormType(Application $application): string
    {
        $code = strtoupper((string) optional($application->program)->code);

        return str_starts_with($code, 'SHS-') ? 'shs' : 'jhs';
    }
}
