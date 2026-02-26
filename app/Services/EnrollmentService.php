<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\EnrollmentSubject;
use App\Models\Grade;
use App\Models\Section;
use App\Models\SectionSubject;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    /**
     * Create a new enrollment for a student assigned to a section.
     * Auto-enrolls in all section_subjects for the student's section.
     *
     * @param int      $studentId
     * @param int      $semesterId
     * @param int|null $sectionId  Override section (defaults to student's assigned section)
     * @return Enrollment
     *
     * @throws \Exception
     */
    public function createEnrollment(int $studentId, int $semesterId, ?int $sectionId = null): Enrollment
    {
        return DB::transaction(function () use ($studentId, $semesterId, $sectionId) {
            $student = Student::findOrFail($studentId);

            // Validate student is eligible for enrollment
            if (!$student->isEnrollable()) {
                throw new \Exception("Student cannot be enrolled. Current status: {$student->status}. Only admitted or active students may enroll.");
            }

            // Check if already enrolled this semester
            $existing = Enrollment::where('student_id', $studentId)
                ->where('semester_id', $semesterId)
                ->first();

            if ($existing) {
                throw new \Exception('Student is already enrolled for this semester.');
            }

            // Determine section
            $sectionId = $sectionId ?? $student->section_id;
            if (!$sectionId) {
                throw new \Exception('Student must be assigned to a section before enrollment.');
            }

            $section = Section::with('sectionSubjects.subject')->findOrFail($sectionId);

            if ($section->isFull()) {
                throw new \Exception("Section {$section->displayName()} is already full.");
            }

            // Calculate total units from section subjects
            $totalUnits = 0;
            foreach ($section->sectionSubjects as $ss) {
                $totalUnits += $ss->subject->totalUnits();
            }

            // Assign student to section if not already
            if ($student->section_id !== $section->id) {
                $student->update(['section_id' => $section->id]);
            }

            // Create enrollment
            $enrollment = Enrollment::create([
                'student_id'   => $studentId,
                'semester_id'  => $semesterId,
                'status'       => 'pending',
                'total_units'  => $totalUnits,
                'total_amount' => 0,
            ]);

            // Auto-enroll in all section subjects
            foreach ($section->sectionSubjects as $ss) {
                EnrollmentSubject::create([
                    'enrollment_id' => $enrollment->id,
                    'section_id'    => $section->id,
                    'subject_id'    => $ss->subject_id,
                ]);
            }

            // Activate student on first enrollment (admitted → active)
            if ($student->isAdmitted()) {
                $student->activate();
            }

            return $enrollment;
        });
    }

    /**
     * Finalize enrollment after payment is verified.
     */
    public function finalizeEnrollment(Enrollment $enrollment): Enrollment
    {
        return DB::transaction(function () use ($enrollment) {
            if (!$enrollment->isFullyPaid()) {
                throw new \Exception('Enrollment cannot be finalized. Payment is not fully verified.');
            }

            $enrollment->update([
                'status'      => 'enrolled',
                'enrolled_at' => now(),
            ]);

            // Create grade records — resolve faculty from section_subjects
            foreach ($enrollment->enrollmentSubjects as $es) {
                $sectionSubject = SectionSubject::where('section_id', $es->section_id)
                    ->where('subject_id', $es->subject_id)
                    ->first();

                Grade::firstOrCreate(
                    [
                        'enrollment_subject_id' => $es->id,
                        'student_id'            => $enrollment->student_id,
                    ],
                    [
                        'faculty_id' => $sectionSubject?->faculty_id,
                        'remarks'    => 'incomplete',
                    ]
                );
            }

            return $enrollment;
        });
    }
}
