<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\GradeService;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function __construct(
        protected GradeService $gradeService,
    ) {}

    /**
     * View finalized grades only, grouped by Academic Year & Semester.
     */
    public function index(): View
    {
        $student = auth()->user()->student;

        $grades = $student
            ? $this->gradeService->getStudentGrades($student->id)
            : collect();

        return view('student.grades', compact('grades'));
    }
}
