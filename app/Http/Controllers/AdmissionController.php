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
