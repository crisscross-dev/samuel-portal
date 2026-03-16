<?php

namespace App\Policies;

use App\Models\Grade;
use App\Models\SectionSubject;
use App\Models\User;

class GradePolicy
{
    /**
     * Admin & Registrar can view all grades.
     * Faculty can view grades for their assigned sections.
     * Students can only view their own finalized grades.
     */
    public function view(User $user, Grade $grade): bool
    {
        if ($user->hasAnyRole(['admin', 'registrar', 'jhs-registrar', 'shs-registrar'])) {
            return true;
        }

        if ($user->hasRole('faculty') && $user->faculty) {
            return $grade->faculty_id === $user->faculty->id;
        }

        if ($user->hasRole('student') && $user->student) {
            return $grade->student_id === $user->student->id && $grade->is_finalized;
        }

        return false;
    }

    /**
     * Faculty can encode/edit grades only for their assigned section-subjects
     * and only when is_finalized = false.
     */
    public function update(User $user, Grade $grade): bool
    {
        if ($grade->is_finalized) {
            return false;
        }

        if ($user->hasRole('faculty') && $user->faculty) {
            return $grade->faculty_id === $user->faculty->id;
        }

        return false;
    }

    /**
     * Faculty can encode grades for a section-subject they are assigned to.
     */
    public function encodeForSectionSubject(User $user, SectionSubject $sectionSubject): bool
    {
        if (!$user->hasRole('faculty') || !$user->faculty) {
            return false;
        }

        return $sectionSubject->faculty_id === $user->faculty->id;
    }

    /**
     * Faculty can finalize grades for their assigned section-subjects.
     */
    public function finalize(User $user, Grade $grade): bool
    {
        if ($grade->is_finalized) {
            return false;
        }

        if ($user->hasRole('faculty') && $user->faculty) {
            return $grade->faculty_id === $user->faculty->id;
        }

        return false;
    }

    /**
     * Only Admin, Registrar, and Department Head can reopen finalized grades.
     */
    public function reopen(User $user, Grade $grade): bool
    {
        if (!$grade->is_finalized) {
            return false;
        }

        if ($user->hasAnyRole(['admin', 'registrar', 'jhs-registrar', 'shs-registrar'])) {
            return true;
        }

        // Department Head can reopen grades in their department
        if ($user->hasRole('faculty') && $user->faculty) {
            $headed = $user->faculty->headedDepartment;
            if ($headed) {
                $es = $grade->enrollmentSubject;
                return $es?->section?->gradeLevel?->department_id === $headed->id;
            }
        }

        return false;
    }

    /**
     * Admin and Registrar can view all grades index.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'registrar', 'jhs-registrar', 'shs-registrar', 'faculty', 'student']);
    }
}
