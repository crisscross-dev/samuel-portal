<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;
        $semester = Semester::current();
        $enrollment = $student?->currentEnrollment();

        $stats = [
            'enrollment_status' => $enrollment?->status ?? 'Not Enrolled',
            'total_units'       => $enrollment?->total_units ?? 0,
            'total_amount'      => $enrollment?->total_amount ?? 0,
            'total_paid'        => $enrollment?->totalPaid() ?? 0,
            'balance'           => $enrollment?->balance() ?? 0,
        ];

        return view('student.dashboard', compact('student', 'semester', 'enrollment', 'stats'));
    }
}
