<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\GradeAuditLog;
use App\Models\SectionSubject;
use App\Models\Semester;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function __construct(
        protected GradeService $gradeService,
    ) {}

    /**
     * View all grades with filtering.
     */
    public function index(Request $request): View
    {
        $semesters = Semester::with('academicYear')->orderByDesc('start_date')->get();

        $query = Grade::with([
            'student.user',
            'faculty.user',
            'enrollmentSubject.subject',
            'enrollmentSubject.section.gradeLevel.department',
            'enrollmentSubject.section.academicYear',
        ]);

        if ($request->filled('semester_id')) {
            $query->whereHas('enrollmentSubject.enrollment', fn ($q) => $q->where('semester_id', $request->semester_id));
        }

        if ($request->filled('section_subject_id')) {
            $ss = SectionSubject::find($request->section_subject_id);
            if ($ss) {
                $query->whereHas('enrollmentSubject', fn ($q) =>
                    $q->where('section_id', $ss->section_id)
                      ->where('subject_id', $ss->subject_id)
                );
            }
        }

        if ($request->filled('status')) {
            if ($request->status === 'finalized') {
                $query->where('is_finalized', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_finalized', false);
            }
        }

        $grades = $query->orderByDesc('updated_at')->paginate(25)->withQueryString();

        // Get section-subjects for filter
        $sectionSubjects = $request->filled('semester_id')
            ? SectionSubject::with(['section.gradeLevel', 'subject'])
                ->whereHas('section.enrollmentSubjects.enrollment', fn ($q) => $q->where('semester_id', $request->semester_id))
                ->get()
            : collect();

        return view('admin.grades.index', compact('grades', 'semesters', 'sectionSubjects'));
    }

    /**
     * Reopen a single finalized grade.
     */
    public function reopen(Grade $grade): RedirectResponse
    {
        if (!$grade->is_finalized) {
            return back()->with('error', 'This grade is not finalized.');
        }

        $this->gradeService->reopenGrade($grade);

        return back()->with('success', 'Grade reopened for editing.');
    }

    /**
     * Reopen all finalized grades for a section-subject.
     */
    public function reopenSectionSubject(SectionSubject $sectionSubject): RedirectResponse
    {
        $count = $this->gradeService->reopenSectionSubjectGrades($sectionSubject);

        return back()->with('success', "{$count} grade(s) reopened for {$sectionSubject->label()}.");
    }

    /**
     * View audit trail for all grades or filtered.
     */
    public function auditLog(Request $request): View
    {
        $query = GradeAuditLog::with(['user', 'grade.student.user', 'grade.enrollmentSubject.subject'])
            ->orderByDesc('performed_at');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(30)->withQueryString();

        return view('admin.grades.audit', compact('logs'));
    }
}
