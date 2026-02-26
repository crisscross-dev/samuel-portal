<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * View class schedule for current semester.
     */
    public function index(): View
    {
        $student = auth()->user()->student;
        $semester = Semester::current();
        $enrollment = $student?->currentEnrollment();

        $schedule = collect();

        if ($enrollment) {
            $enrollment->load('enrollmentSubjects.section.subject', 'enrollmentSubjects.section.faculty.user');

            $schedule = $enrollment->enrollmentSubjects->map(function ($es) {
                return [
                    'subject_code' => $es->subject->code,
                    'subject_name' => $es->subject->name,
                    'section_name' => $es->section->name,
                    'schedule'     => $es->section->schedule ?? 'TBA',
                    'room'         => $es->section->room ?? 'TBA',
                    'instructor'   => $es->section->faculty?->user?->name ?? 'TBA',
                ];
            });
        }

        return view('student.schedule', compact('schedule', 'semester'));
    }
}
