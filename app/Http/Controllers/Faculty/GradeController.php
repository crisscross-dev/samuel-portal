<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\GradeAuditLog;
use App\Models\SectionSubject;
use App\Services\GradeImportService;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GradeController extends Controller
{
    public function __construct(
        protected GradeService $gradeService,
        protected GradeImportService $importService,
    ) {}

    /**
     * Show grade encoding form for a section-subject.
     */
    public function edit(SectionSubject $sectionSubject): View
    {
        Gate::authorize('encodeForSectionSubject', $sectionSubject);

        $sectionSubject->load([
            'subject',
            'section.gradeLevel.department',
            'section.academicYear',
        ]);

        $enrollmentSubjects = \App\Models\EnrollmentSubject::where('section_id', $sectionSubject->section_id)
            ->where('subject_id', $sectionSubject->subject_id)
            ->whereHas('enrollment', fn ($e) => $e->where('status', 'enrolled'))
            ->with(['enrollment.student.user', 'grade'])
            ->get();

        $stats = $this->gradeService->getSectionSubjectGradeStats($sectionSubject);

        return view('faculty.grades.edit', compact('sectionSubject', 'enrollmentSubjects', 'stats'));
    }

    /**
     * Save draft grades (batch update) — does NOT finalize.
     */
    public function update(Request $request, SectionSubject $sectionSubject): RedirectResponse
    {
        Gate::authorize('encodeForSectionSubject', $sectionSubject);

        $request->validate([
            'grades'              => ['required', 'array'],
            'grades.*.final_grade' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $faculty = auth()->user()->faculty;

        $saved = $this->gradeService->saveDraftGrades(
            $sectionSubject,
            $faculty->id,
            $request->input('grades', [])
        );

        return back()->with('success', "{$saved} grade(s) saved as draft.");
    }

    /**
     * Finalize all draft grades for a section-subject.
     */
    public function finalize(Request $request, SectionSubject $sectionSubject): RedirectResponse
    {
        Gate::authorize('encodeForSectionSubject', $sectionSubject);

        $faculty = auth()->user()->faculty;

        try {
            $count = $this->gradeService->finalizeGrades($sectionSubject, $faculty->id);
            return back()->with('success', "{$count} grade(s) finalized successfully. They are now read-only.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Bulk import grades from CSV.
     */
    public function import(Request $request, SectionSubject $sectionSubject): RedirectResponse
    {
        Gate::authorize('encodeForSectionSubject', $sectionSubject);

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $parsed = $this->importService->parseCSV($request->file('csv_file'));

        if (!empty($parsed['errors'])) {
            return back()->with('error', 'CSV errors: ' . implode(' | ', array_slice($parsed['errors'], 0, 5)));
        }

        if (empty($parsed['data'])) {
            return back()->with('error', 'No valid data found in the CSV file.');
        }

        $faculty = auth()->user()->faculty;
        $result  = $this->gradeService->bulkImportGrades($sectionSubject, $faculty->id, $parsed['data']);

        $msg = "{$result['imported']} grade(s) imported.";
        if ($result['skipped'] > 0) {
            $msg .= " {$result['skipped']} skipped.";
        }
        if (!empty($result['errors'])) {
            $msg .= ' Errors: ' . implode(' | ', array_slice($result['errors'], 0, 5));
        }

        return back()->with($result['imported'] > 0 ? 'success' : 'error', $msg);
    }

    /**
     * Download CSV template for grade import.
     */
    public function downloadTemplate(SectionSubject $sectionSubject): StreamedResponse
    {
        Gate::authorize('encodeForSectionSubject', $sectionSubject);

        // Build real template with enrolled students
        $enrollmentSubjects = \App\Models\EnrollmentSubject::where('section_id', $sectionSubject->section_id)
            ->where('subject_id', $sectionSubject->subject_id)
            ->whereHas('enrollment', fn ($e) => $e->where('status', 'enrolled'))
            ->with('enrollment.student')
            ->get();

        $filename = "grades_template_{$sectionSubject->subject->code}_{$sectionSubject->section->name}.csv";

        return response()->streamDownload(function () use ($enrollmentSubjects) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['student_id', 'final_grade']);
            foreach ($enrollmentSubjects as $es) {
                fputcsv($handle, [$es->enrollment->student->student_id ?? '', '']);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * View audit trail for a section-subject's grades.
     */
    public function auditLog(SectionSubject $sectionSubject): View
    {
        Gate::authorize('encodeForSectionSubject', $sectionSubject);

        $sectionSubject->load(['subject', 'section.gradeLevel.department']);

        $logs = GradeAuditLog::whereHas('grade.enrollmentSubject', fn ($q) =>
                $q->where('section_id', $sectionSubject->section_id)
                  ->where('subject_id', $sectionSubject->subject_id)
            )
            ->with(['user', 'grade.student.user'])
            ->orderByDesc('performed_at')
            ->paginate(30);

        return view('faculty.grades.audit', compact('sectionSubject', 'logs'));
    }
}
