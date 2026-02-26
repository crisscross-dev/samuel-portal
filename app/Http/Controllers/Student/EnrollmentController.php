<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\AssessmentService;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(
        private AssessmentService $assessmentService,
    ) {}

    /**
     * View enrollment status and history.
     */
    public function index(): View
    {
        $student = auth()->user()->student;

        $enrollments = $student
            ? $student->enrollments()
                ->with(['semester.academicYear', 'enrollmentSubjects.subject', 'enrollmentSubjects.section', 'payments'])
                ->latest()
                ->get()
            : collect();

        return view('student.enrollment', compact('enrollments', 'student'));
    }

    /**
     * View payment status for a specific enrollment.
     */
    public function payments(Enrollment $enrollment): View
    {
        $student = auth()->user()->student;

        // Ensure student can only view their own enrollment
        if ($enrollment->student_id !== $student?->id) {
            abort(403);
        }

        $enrollment->load('payments');
        $breakdown = $this->assessmentService->getBreakdown($enrollment);

        return view('student.payments', compact('enrollment', 'breakdown'));
    }
}
