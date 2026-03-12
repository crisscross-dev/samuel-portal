<?php

namespace App\Services;

use App\Mail\AdmissionConfirmed;
use App\Models\Application;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdmissionService
{
    /**
     * Approve an application and convert the applicant into a student.
     *
     * Transaction flow:
     *  1. Mark application → approved
     *  2. Create User account (role = student)
     *  3. Create Student profile (status = admitted)
     *  4. Generate unique student_id
     *
     * @throws \Exception
     */
    public function approveApplication(Application $application, mixed $reviewerId, ?string $remarks = null): Student
    {
        if (!$application->isPending()) {
            throw new \Exception('Only pending applications can be approved.');
        }

        $reviewerId = $reviewerId ? (int) $reviewerId : null;

        $result = DB::transaction(function () use ($application, $reviewerId, $remarks) {
            // 1. Mark application as approved
            $application->update([
                'status'      => 'approved',
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
                'remarks'     => $remarks,
            ]);

            // 2. Create user account
            $user = User::create([
                'name'     => $application->fullName(),
                'email'    => $application->email,
                'password' => 'password', // Default password — student should change on first login
            ]);

            $user->assignRole('student');

            // 3. Create student profile with status = admitted
            $student = Student::create([
                'user_id'          => $user->id,
                'student_id'       => $this->generateStudentId(),
                'program_id'       => $application->program_applied_id,
                'year_level'       => $application->year_level,
                'status'           => 'admitted',
                'date_of_birth'    => $application->date_of_birth,
                'gender'           => $application->gender,
                'address'          => $application->address,
                'contact_number'   => $application->contact_number,
                'guardian_name'    => $application->guardian_name,
                'guardian_contact' => $application->guardian_contact,
                'admission_date'   => now(),
            ]);

            return $student;
        });

        // Send confirmation email outside the transaction so a mail failure
        // doesn't roll back the student account creation.
        try {
            $application->loadMissing('program', 'examSchedule');
            Mail::to($application->email)->send(new AdmissionConfirmed($application, $result));
        } catch (\Throwable) {
            // Log silently — student account already created.
            logger()->error('Failed to send AdmissionConfirmed email to ' . $application->email);
        }

        return $result;
    }

    /**
     * Reject an application. No user account is created.
     */
    public function rejectApplication(Application $application, mixed $reviewerId, ?string $remarks = null): void
    {
        if (!$application->isPending()) {
            throw new \Exception('Only pending applications can be rejected.');
        }

        $application->update([
            'status'      => 'rejected',
            'reviewed_by' => $reviewerId ? (int) $reviewerId : null,
            'reviewed_at' => now(),
            'remarks'     => $remarks,
        ]);
    }

    /**
     * Generate a unique student ID in format: YYYY-NNNNN
     * e.g., 2026-00042
     */
    private function generateStudentId(): string
    {
        $year  = now()->year;
        $last  = Student::where('student_id', 'like', "{$year}-%")
            ->orderByRaw("CAST(SUBSTRING_INDEX(student_id, '-', -1) AS UNSIGNED) DESC")
            ->value('student_id');

        $next = 1;
        if ($last) {
            $next = (int) Str::afterLast($last, '-') + 1;
        }

        return sprintf('%d-%05d', $year, $next);
    }
}
