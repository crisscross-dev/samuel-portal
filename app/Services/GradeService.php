<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\GradeAuditLog;
use App\Models\Section;
use App\Models\SectionSubject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GradeService
{
    /**
     * Save draft grades for a section-subject (batch update).
     * Validates faculty ownership internally. Logs every change.
     */
    public function saveDraftGrades(SectionSubject $sectionSubject, int $facultyId, array $gradesData): int
    {
        $saved  = 0;
        $userId = auth()->id();

        DB::transaction(function () use ($sectionSubject, $facultyId, $gradesData, &$saved, $userId) {
            foreach ($gradesData as $gradeId => $data) {
                $grade = Grade::where('id', $gradeId)
                    ->where('faculty_id', $facultyId)
                    ->where('is_finalized', false)
                    ->whereHas('enrollmentSubject', fn ($q) =>
                        $q->where('section_id', $sectionSubject->section_id)
                          ->where('subject_id', $sectionSubject->subject_id)
                    )
                    ->first();

                if (!$grade) {
                    continue;
                }

                $oldGrade   = $grade->final_grade;
                $oldRemarks = $grade->remarks;

                $finalGrade = isset($data['final_grade']) && $data['final_grade'] !== ''
                    ? (float) $data['final_grade']
                    : null;

                $grade->setFinalGrade($finalGrade);
                $grade->save();

                // Audit log
                if ($oldGrade != $finalGrade) {
                    GradeAuditLog::log(
                        grade: $grade,
                        userId: $userId,
                        action: $oldGrade === null ? 'created' : 'updated',
                        oldGrade: $oldGrade,
                        newGrade: $finalGrade,
                        oldRemarks: $oldRemarks,
                        newRemarks: $grade->remarks,
                    );
                }

                $saved++;
            }
        });

        return $saved;
    }

    /**
     * Bulk import grades from parsed CSV/Excel data.
     */
    public function bulkImportGrades(SectionSubject $sectionSubject, int $facultyId, array $importData): array
    {
        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $userId   = auth()->id();

        DB::transaction(function () use ($sectionSubject, $facultyId, $importData, &$imported, &$skipped, &$errors, $userId) {
            foreach ($importData as $row) {
                $studentId  = $row['student_id'] ?? null;
                $finalGrade = $row['final_grade'] ?? null;

                if (!$studentId || $finalGrade === null || $finalGrade === '') {
                    $skipped++;
                    continue;
                }

                $finalGrade = (float) $finalGrade;
                if ($finalGrade < 0 || $finalGrade > 100) {
                    $errors[] = "Student {$studentId}: Grade {$finalGrade} out of range (0-100).";
                    continue;
                }

                // Find the grade record matching student + section + subject
                $grade = Grade::where('faculty_id', $facultyId)
                    ->where('is_finalized', false)
                    ->whereHas('enrollmentSubject', fn ($q) =>
                        $q->where('section_id', $sectionSubject->section_id)
                          ->where('subject_id', $sectionSubject->subject_id)
                    )
                    ->whereHas('student', fn ($q) => $q->where('student_id', $studentId))
                    ->first();

                if (!$grade) {
                    $errors[] = "Student {$studentId}: No matching draft grade record found.";
                    continue;
                }

                $oldGrade   = $grade->final_grade;
                $oldRemarks = $grade->remarks;

                $grade->setFinalGrade($finalGrade);
                $grade->save();

                GradeAuditLog::log(
                    grade: $grade,
                    userId: $userId,
                    action: 'imported',
                    oldGrade: $oldGrade,
                    newGrade: $finalGrade,
                    oldRemarks: $oldRemarks,
                    newRemarks: $grade->remarks,
                    notes: 'Bulk CSV/Excel import',
                );

                $imported++;
            }
        });

        return compact('imported', 'skipped', 'errors');
    }

    /**
     * Finalize all draft grades for a section-subject.
     * Returns the number of grades finalized.
     */
    public function finalizeGrades(SectionSubject $sectionSubject, int $facultyId): int
    {
        $grades = Grade::where('faculty_id', $facultyId)
            ->where('is_finalized', false)
            ->whereHas('enrollmentSubject', fn ($q) =>
                $q->where('section_id', $sectionSubject->section_id)
                  ->where('subject_id', $sectionSubject->subject_id)
            )
            ->get();

        if ($grades->isEmpty()) {
            throw new \Exception('No draft grades found to finalize.');
        }

        $incomplete = $grades->filter(fn ($g) => $g->final_grade === null);
        if ($incomplete->isNotEmpty()) {
            throw new \Exception(
                'All students must have a final grade before finalization. '
                . $incomplete->count() . ' grade(s) are still empty.'
            );
        }

        $finalized = 0;
        $userId    = auth()->id();

        DB::transaction(function () use ($grades, &$finalized, $userId) {
            foreach ($grades as $grade) {
                $grade->finalize();

                GradeAuditLog::log(
                    grade: $grade,
                    userId: $userId,
                    action: 'finalized',
                    newGrade: $grade->final_grade,
                    newRemarks: $grade->remarks,
                    notes: 'Batch finalization',
                );

                $finalized++;
            }
        });

        return $finalized;
    }

    /**
     * Reopen a finalized grade (admin/registrar/dept head).
     */
    public function reopenGrade(Grade $grade): void
    {
        $oldGrade   = $grade->final_grade;
        $oldRemarks = $grade->remarks;

        $grade->reopen();

        GradeAuditLog::log(
            grade: $grade,
            userId: auth()->id(),
            action: 'reopened',
            oldGrade: $oldGrade,
            oldRemarks: $oldRemarks,
            notes: 'Single grade reopened',
        );
    }

    /**
     * Reopen all finalized grades for a section-subject (admin/registrar/dept head).
     */
    public function reopenSectionSubjectGrades(SectionSubject $sectionSubject): int
    {
        $grades = Grade::where('is_finalized', true)
            ->whereHas('enrollmentSubject', fn ($q) =>
                $q->where('section_id', $sectionSubject->section_id)
                  ->where('subject_id', $sectionSubject->subject_id)
            )
            ->get();

        $count  = 0;
        $userId = auth()->id();

        DB::transaction(function () use ($grades, &$count, $userId) {
            foreach ($grades as $grade) {
                $oldGrade   = $grade->final_grade;
                $oldRemarks = $grade->remarks;

                $grade->reopen();
                $count++;

                GradeAuditLog::log(
                    grade: $grade,
                    userId: $userId,
                    action: 'reopened',
                    oldGrade: $oldGrade,
                    oldRemarks: $oldRemarks,
                    notes: 'Batch section-subject reopen',
                );
            }
        });

        return $count;
    }

    /**
     * Get finalized grades for a student, grouped by "AY - Semester".
     */
    public function getStudentGrades(int $studentId): Collection
    {
        return Grade::where('student_id', $studentId)
            ->finalized()
            ->with([
                'enrollmentSubject.subject',
                'enrollmentSubject.section',
                'enrollmentSubject.enrollment.semester.academicYear',
                'faculty.user',
            ])
            ->get()
            ->groupBy(function ($g) {
                $semester = $g->enrollmentSubject->enrollment->semester;
                return $semester->academicYear->name . ' — ' . $semester->name;
            });
    }

    /**
     * Get grade statistics for a section-subject.
     * Includes average, highest, lowest, missing count.
     */
    public function getSectionSubjectGradeStats(SectionSubject $sectionSubject): array
    {
        $grades = Grade::whereHas('enrollmentSubject', fn ($q) =>
                $q->where('section_id', $sectionSubject->section_id)
                  ->where('subject_id', $sectionSubject->subject_id)
            )
            ->get();

        $total     = $grades->count();
        $finalized = $grades->where('is_finalized', true)->count();
        $draft     = $total - $finalized;
        $passed    = $grades->where('remarks', 'passed')->count();
        $failed    = $grades->where('remarks', 'failed')->count();
        $encoded   = $grades->whereNotNull('final_grade')->count();
        $missing   = $total - $encoded;

        $encodedGrades = $grades->whereNotNull('final_grade')->pluck('final_grade')->map(fn ($v) => (float) $v);
        $average  = $encodedGrades->isNotEmpty() ? round($encodedGrades->avg(), 2) : null;
        $highest  = $encodedGrades->isNotEmpty() ? $encodedGrades->max() : null;
        $lowest   = $encodedGrades->isNotEmpty() ? $encodedGrades->min() : null;

        return compact('total', 'finalized', 'draft', 'passed', 'failed', 'encoded', 'missing', 'average', 'highest', 'lowest');
    }
}
