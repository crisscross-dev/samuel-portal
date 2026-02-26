<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $semester = Semester::current();

        $stats = [
            'total_students'       => Student::count(),
            'admitted_students'    => Student::where('status', 'admitted')->count(),
            'pending_applications' => Application::pending()->count(),
            'pending_enrollments'  => Enrollment::where('status', 'pending')->count(),
            'assessed_enrollments' => Enrollment::where('status', 'assessed')->count(),
            'enrolled_this_sem'    => $semester
                ? Enrollment::where('semester_id', $semester->id)->where('status', 'enrolled')->count()
                : 0,
            'pending_payments'     => Payment::where('status', 'pending')->count(),
        ];

        $recentEnrollments = Enrollment::with(['student.user', 'semester'])
            ->latest()
            ->take(10)
            ->get();

        $recentApplications = Application::with('program')
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('registrar.dashboard', compact('stats', 'recentEnrollments', 'recentApplications', 'semester'));
    }
}
