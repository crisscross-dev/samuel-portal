<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\SectionSubject;
use Illuminate\View\View;

class SectionController extends Controller
{
    /**
     * View teaching loads (section-subjects) for the current academic year.
     */
    public function index(): View
    {
        $faculty = auth()->user()->faculty;

        $sectionSubjects = $faculty
            ? $faculty->currentTeachingLoads()
                ->with(['section.gradeLevel.department', 'section.academicYear', 'subject'])
                ->get()
            : collect();

        return view('faculty.sections.index', compact('sectionSubjects'));
    }

    /**
     * View class list for a specific section-subject.
     */
    public function show(SectionSubject $sectionSubject): View
    {
        $faculty = auth()->user()->faculty;

        // Ensure faculty only sees their own section-subjects
        if ($sectionSubject->faculty_id !== $faculty?->id) {
            abort(403, 'You are not assigned to this section-subject.');
        }

        $sectionSubject->load([
            'section.gradeLevel.department',
            'section.academicYear',
            'subject',
            'section.enrollmentSubjects' => function ($q) use ($sectionSubject) {
                $q->where('subject_id', $sectionSubject->subject_id)
                  ->whereHas('enrollment', fn ($e) => $e->where('status', 'enrolled'));
            },
            'section.enrollmentSubjects.enrollment.student.user',
            'section.enrollmentSubjects.grade',
        ]);

        return view('faculty.sections.show', compact('sectionSubject'));
    }
}
