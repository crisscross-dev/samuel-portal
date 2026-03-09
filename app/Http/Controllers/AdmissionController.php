<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
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

        // Status is 'pending' by default — applicant must pass entrance
        // exam and complete payment before being converted to a Student.
        Application::create($data);

        return redirect()->route('admission.success');
    }

    /**
     * Look up application status by email.
     */
    public function trackSearch(\Illuminate\Http\Request $request): View
    {
        $request->validate(['email' => 'required|email']);

        $application = Application::with('program')
            ->where('email', $request->email)
            ->latest()
            ->first();

        return view('admission.track', compact('application'));
    }
}
