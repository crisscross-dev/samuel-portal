<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\StudentAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentAccountController extends Controller
{
    public function __construct(
        private readonly StudentAccountService $studentAccountService,
    ) {}

    public function index(): View
    {
        $programPrefix = $this->programPrefixForCurrentRegistrar();
        $pendingAccounts = $this->studentAccountService->getPendingReleaseQueue(15, $programPrefix);

        return view('registrar.student_accounts.index', compact('pendingAccounts'));
    }

    public function release(Application $application): RedirectResponse
    {
        try {
            $released = $this->studentAccountService->releasePortalAccount($application);

            return back()->with(
                'success',
                'Student account released. Student ID: ' . $released['student']->student_id
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function programPrefixForCurrentRegistrar(): ?string
    {
        $user = request()->user();

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
}
