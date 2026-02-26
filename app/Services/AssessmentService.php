<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\TuitionStructure;

class AssessmentService
{
    /**
     * Generate assessment (tuition computation) for an enrollment.
     * Resolves the active TuitionStructure for the student's department + academic year,
     * locks it on the enrollment record, and computes the total.
     *
     * @throws \RuntimeException if no active tuition structure is found
     */
    public function generateAssessment(Enrollment $enrollment): Enrollment
    {
        $enrollment->load([
            'enrollmentSubjects.subject',
            'semester.academicYear',
            'student.section.gradeLevel.department',
        ]);

        $semester    = $enrollment->semester;
        $academicYear = $semester?->academicYear;
        $department  = $enrollment->student?->section?->gradeLevel?->department;

        if (!$academicYear) {
            throw new \RuntimeException('Enrollment has no associated academic year.');
        }

        if (!$department) {
            throw new \RuntimeException('Student has no assigned section/department.');
        }

        $structure = TuitionStructure::findActive($department->id, $academicYear->id);

        if (!$structure) {
            throw new \RuntimeException(
                "No active tuition structure found for department \"{$department->name}\" in academic year \"{$academicYear->name}\"."
            );
        }

        $totalAmount = $structure->computeTotal($enrollment->enrollmentSubjects);

        $enrollment->update([
            'tuition_structure_id' => $structure->id,
            'total_amount'         => $totalAmount,
            'status'               => 'assessed',
        ]);

        return $enrollment->fresh();
    }

    /**
     * Get itemised assessment breakdown for display.
     * Uses the locked tuition_structure if present, otherwise falls back to active.
     */
    public function getBreakdown(Enrollment $enrollment): array
    {
        $enrollment->load([
            'enrollmentSubjects.subject',
            'semester.academicYear',
            'student.section.gradeLevel.department',
            'tuitionStructure',
        ]);

        $structure = $enrollment->tuitionStructure;

        // Fallback: look up active structure (for display before assessment)
        if (!$structure) {
            $academicYear = $enrollment->semester?->academicYear;
            $department   = $enrollment->student?->section?->gradeLevel?->department;
            if ($academicYear && $department) {
                $structure = TuitionStructure::findActive($department->id, $academicYear->id);
            }
        }

        if (!$structure) {
            return [];
        }

        $miscFee = (float) $structure->misc_fee;
        $regFee  = (float) $structure->reg_fee;

        if ($structure->pricing_type === 'flat') {
            return [
                'pricing_type' => 'flat',
                'flat_amount'  => (float) $structure->flat_amount,
                'misc_fee'     => $miscFee,
                'reg_fee'      => $regFee,
                'total'        => $enrollment->total_amount,
                'total_paid'   => $enrollment->totalPaid(),
                'balance'      => $enrollment->balance(),
                'structure'    => $structure,
            ];
        }

        // per_unit
        $items = [];
        $lectureRate = (float) $structure->lecture_rate;
        $labRate     = (float) $structure->lab_rate;

        foreach ($enrollment->enrollmentSubjects as $es) {
            $subject     = $es->subject;
            $lectureCost = $subject->lecture_units * $lectureRate;
            $labCost     = $subject->lab_units * $labRate;

            $items[] = [
                'subject_code'  => $subject->code,
                'subject_name'  => $subject->name,
                'lecture_units' => $subject->lecture_units,
                'lab_units'     => $subject->lab_units,
                'lecture_cost'  => $lectureCost,
                'lab_cost'      => $labCost,
                'subtotal'      => $lectureCost + $labCost,
            ];
        }

        return [
            'pricing_type' => 'per_unit',
            'items'        => $items,
            'misc_fee'     => $miscFee,
            'reg_fee'      => $regFee,
            'total'        => $enrollment->total_amount,
            'total_paid'   => $enrollment->totalPaid(),
            'balance'      => $enrollment->balance(),
            'structure'    => $structure,
        ];
    }
}
