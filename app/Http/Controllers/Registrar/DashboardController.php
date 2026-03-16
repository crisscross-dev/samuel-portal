<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $semester = Semester::current();
        $programPrefix = $this->programPrefixForCurrentRegistrar();

        $pendingApplicationsQuery = Application::pending();
        $recentApplicationsQuery = Application::with('program')->pending();

        if ($programPrefix !== null) {
            $pendingApplicationsQuery->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'));
            $recentApplicationsQuery->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'));
        }

        $stats = [
            'total_students'       => Student::count(),
            'admitted_students'    => Student::where('status', 'admitted')->count(),
            'pending_applications' => $pendingApplicationsQuery->count(),
            'pending_review'       => (clone $pendingApplicationsQuery)->count(),
            'approved_for_exam'    => Application::where('workflow_stage', Application::WORKFLOW_EXAM_APPROVED)->count(),
            'guidance_queue'       => Application::where('workflow_stage', Application::WORKFLOW_GUIDANCE_REVIEW)->count(),
            'requirements_check'   => Application::where('workflow_stage', Application::WORKFLOW_REGISTRAR_REQUIREMENTS)->count(),
            'enrollment_stage'     => Application::where('workflow_stage', Application::WORKFLOW_ENROLLMENT)->count(),
            'cashier_inactive'     => Application::where('workflow_stage', Application::WORKFLOW_CASHIER_PAYMENT)->count(),
            'inactive'             => Application::where('is_active', false)->count(),
            'pending_enrollments'  => Enrollment::where('status', 'pending')->count(),
            'assessed_enrollments' => Enrollment::where('status', 'assessed')->count(),
            'enrolled_this_sem'    => $semester
                ? Enrollment::where('semester_id', $semester->id)->where('status', 'enrolled')->count()
                : 0,
            'pending_payments'     => Payment::where('status', 'pending')->count(),
        ];

        if ($programPrefix !== null) {
            $stats['approved_for_exam'] = Application::where('workflow_stage', Application::WORKFLOW_EXAM_APPROVED)
                ->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'))
                ->count();

            $stats['guidance_queue'] = Application::where('workflow_stage', Application::WORKFLOW_GUIDANCE_REVIEW)
                ->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'))
                ->count();

            $stats['requirements_check'] = Application::where('workflow_stage', Application::WORKFLOW_REGISTRAR_REQUIREMENTS)
                ->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'))
                ->count();

            $stats['enrollment_stage'] = Application::where('workflow_stage', Application::WORKFLOW_ENROLLMENT)
                ->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'))
                ->count();

            $stats['cashier_inactive'] = Application::where('workflow_stage', Application::WORKFLOW_CASHIER_PAYMENT)
                ->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'))
                ->count();

            $stats['inactive'] = Application::where('is_active', false)
                ->whereHas('program', fn($programQuery) => $programQuery->where('code', 'like', $programPrefix . '%'))
                ->count();
        }

        $recentEnrollments = Enrollment::with(['student.user', 'semester'])
            ->latest()
            ->take(10)
            ->get();

        $recentApplications = $recentApplicationsQuery
            ->latest()
            ->take(5)
            ->get();

        return view('registrar.dashboard', compact('stats', 'recentEnrollments', 'recentApplications', 'semester'));
    }

    private function programPrefixForCurrentRegistrar(): ?string
    {
        $user = Auth::user();

        if (!$user instanceof User) {
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
