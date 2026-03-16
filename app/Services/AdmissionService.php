<?php

namespace App\Services;

use App\Mail\EntranceExamApprovedMail;
use App\Mail\InterviewScheduleMail;
use App\Models\Application;
use App\Models\GuidanceInterviewSlot;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdmissionService
{
    /**
     * Approve an application for the entrance examination.
     * @throws \Exception
     */
    public function approveForExam(Application $application, mixed $reviewerId, ?string $remarks = null): Application
    {
        if (!$application->isPending()) {
            throw new \Exception('Only pending applications can be approved.');
        }

        if (!$application->admissionPayment || $application->admissionPayment->payment_status !== 'paid') {
            throw new \Exception('Verified payment is required before approving an applicant for the entrance exam.');
        }

        if (!$application->exam_schedule_id && !$application->exam_schedule) {
            throw new \Exception('Assign an exam schedule before approving the applicant for the entrance exam.');
        }

        $reviewerId = $reviewerId ? (int) $reviewerId : null;

        $result = DB::transaction(function () use ($application, $reviewerId, $remarks) {
            $application->update([
                'status'      => 'approved',
                'workflow_stage' => Application::WORKFLOW_EXAM_APPROVED,
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
                'remarks'     => $remarks,
                'is_active'   => true,
                'exam_result' => null,
                'exam_remarks' => null,
                'exam_result_recorded_by' => null,
                'exam_result_recorded_at' => null,
                'forwarded_to_guidance_at' => null,
                'interview_date' => null,
                'guidance_user_id' => null,
                'guidance_remarks' => null,
                'interview_form_token' => null,
                'interview_form_sent_at' => null,
                'interview_form_submitted_at' => null,
            ]);

            return $application->fresh(['program', 'reviewer', 'examSchedule']);
        });

        try {
            Mail::to($result->email)->send(new EntranceExamApprovedMail($result));
        } catch (\Throwable) {
            logger()->error('Failed to send EntranceExamApprovedMail email to ' . $result->email);
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
            'workflow_stage' => Application::WORKFLOW_REJECTED,
            'reviewed_by' => $reviewerId ? (int) $reviewerId : null,
            'reviewed_at' => now(),
            'remarks'     => $remarks,
            'is_active'   => false,
        ]);
    }

    /**
     * Record the registrar's exam result.
     * Failed applicants stay in the system as archived records.
     * Passed applicants are forwarded to guidance.
     *
     * @throws \Exception
     */
    public function recordExamResult(Application $application, mixed $reviewerId, string $result, ?string $remarks = null): Application
    {
        if (!$application->isExamApproved()) {
            throw new \Exception('Only applicants approved for the entrance exam can receive an exam result.');
        }

        if (!in_array($result, [Application::EXAM_RESULT_PASSED, Application::EXAM_RESULT_FAILED], true)) {
            throw new \Exception('Invalid exam result.');
        }

        return DB::transaction(function () use ($application, $reviewerId, $result, $remarks) {
            $application->update([
                'exam_result' => $result,
                'exam_remarks' => $remarks,
                'exam_result_recorded_by' => $reviewerId ? (int) $reviewerId : null,
                'exam_result_recorded_at' => now(),
                'workflow_stage' => $result === Application::EXAM_RESULT_PASSED
                    ? Application::WORKFLOW_GUIDANCE_REVIEW
                    : Application::WORKFLOW_EXAM_FAILED,
                'forwarded_to_guidance_at' => $result === Application::EXAM_RESULT_PASSED ? now() : null,
                'is_active' => $result === Application::EXAM_RESULT_PASSED,
                'is_archived' => $result === Application::EXAM_RESULT_FAILED,
                'archived_at' => $result === Application::EXAM_RESULT_FAILED ? now() : null,
                'archive_reason' => $result === Application::EXAM_RESULT_FAILED
                    ? ($remarks ?: 'Failed entrance examination.')
                    : null,
            ]);

            return $application->fresh(['program', 'reviewer', 'examReviewer', 'guidanceUser', 'examSchedule']);
        });
    }

    /**
     * Schedule the applicant's guidance interview and send the follow-up form link.
     *
     * @throws \Exception
     */
    public function scheduleInterview(Application $application, mixed $guidanceUserId, ?string $remarks = null): Application
    {
        if (!in_array($application->workflow_stage, [
            Application::WORKFLOW_GUIDANCE_REVIEW,
            Application::WORKFLOW_INTERVIEW_FORM_SUBMITTED,
        ], true)) {
            throw new \Exception('Only queued or submitted guidance cases can receive a form link.');
        }

        $result = DB::transaction(function () use ($application, $guidanceUserId, $remarks) {
            $token = $application->interview_form_token ?: Str::random(64);

            $application->update([
                'workflow_stage' => Application::WORKFLOW_GUIDANCE_REVIEW,
                'guidance_user_id' => $guidanceUserId ? (int) $guidanceUserId : null,
                'guidance_remarks' => $remarks,
                'interview_form_token' => $token,
                'interview_form_sent_at' => now(),
            ]);

            return $application->fresh(['program', 'guidanceUser']);
        });

        try {
            Mail::to($result->email)->send(new InterviewScheduleMail($result));
        } catch (\Throwable) {
            logger()->error('Failed to send InterviewScheduleMail email to ' . $result->email);
        }

        return $result;
    }

    /**
     * Persist the applicant's guidance follow-up form.
     */
    public function submitInterviewForm(Application $application, array $data): Application
    {
        return DB::transaction(function () use ($application, $data) {
            $lockedApplication = Application::whereKey($application->id)->lockForUpdate()->firstOrFail();

            if ($lockedApplication->interview_form_submitted_at) {
                throw new \Exception('Interview form can only be submitted once.');
            }

            $slotId = (int) ($data['interview_slot_id'] ?? 0);
            if ($slotId <= 0) {
                throw new \Exception('Please select an available interview schedule.');
            }

            $slot = GuidanceInterviewSlot::whereKey($slotId)->lockForUpdate()->first();
            if (!$slot || !$slot->is_active || $slot->interview_date->isPast()) {
                throw new \Exception('Selected interview schedule is no longer available.');
            }

            $isOccupied = Application::where('interview_slot_id', $slot->id)
                ->where('id', '!=', $application->id)
                ->exists();

            if ($isOccupied) {
                throw new \Exception('Selected interview schedule is already occupied. Please choose another slot.');
            }

            unset($data['interview_slot_id']);

            $lockedApplication->update(array_merge($data, [
                'interview_slot_id' => $slot->id,
                'interview_date' => $slot->interview_date,
                'workflow_stage' => Application::WORKFLOW_INTERVIEW_FORM_SUBMITTED,
                'interview_form_submitted_at' => now(),
                'is_active' => true,
            ]));

            return $lockedApplication->fresh(['program', 'guidanceUser', 'interviewSlot']);
        });
    }

    /**
     * Record the Guidance Office interview decision.
     * Failed applicants are archived for reference. Passed applicants return to Registrar.
     *
     * @throws \Exception
     */
    public function recordInterviewResult(Application $application, mixed $guidanceUserId, string $result, ?string $remarks = null): Application
    {
        if (!$application->isReadyForInterviewEvaluation()) {
            throw new \Exception('Only scheduled or completed interview cases can be evaluated.');
        }

        if (!in_array($result, [
            Application::INTERVIEW_RESULT_PASSED,
            Application::INTERVIEW_RESULT_FAILED,
            Application::INTERVIEW_RESULT_CONSIDERED,
        ], true)) {
            throw new \Exception('Invalid interview result.');
        }

        return DB::transaction(function () use ($application, $guidanceUserId, $result, $remarks) {
            $attributes = [
                'interview_result' => $result,
                'interview_remarks' => $remarks,
                'interview_evaluated_by' => $guidanceUserId ? (int) $guidanceUserId : null,
                'interview_evaluated_at' => now(),
                'guidance_remarks' => $remarks,
            ];

            if ($result === Application::INTERVIEW_RESULT_FAILED) {
                $attributes = array_merge($attributes, [
                    'workflow_stage' => Application::WORKFLOW_ARCHIVED,
                    'is_active' => false,
                    'is_archived' => true,
                    'archived_at' => now(),
                    'archive_reason' => $remarks ?: 'Failed guidance interview.',
                ]);
            } else {
                $attributes = array_merge($attributes, [
                    'workflow_stage' => Application::WORKFLOW_REGISTRAR_REQUIREMENTS,
                    'returned_to_registrar_at' => now(),
                    'is_archived' => false,
                    'archived_at' => null,
                    'archive_reason' => null,
                    'is_active' => true,
                ]);
            }

            $application->update($attributes);

            if ($application->interview_slot_id) {
                GuidanceInterviewSlot::whereKey($application->interview_slot_id)->update([
                    'is_active' => false,
                    'deactivated_at' => now(),
                    'deactivation_reason' => 'completed',
                ]);
            }

            return $application->fresh(['program', 'guidanceUser', 'interviewEvaluator']);
        });
    }

    /**
     * Verify Registrar requirements and advance the workflow to enrollment when complete.
     *
     * @throws \Exception
     */
    public function verifyRequirements(Application $application, mixed $reviewerId, array $requirements, ?string $remarks = null): Application
    {
        if (!$application->isInRegistrarRequirements()) {
            throw new \Exception('Only applicants returned to the Registrar can have requirements verified.');
        }

        $reviewerId = $reviewerId ? (int) $reviewerId : null;
        $allComplete = collect($requirements)->every(fn($value) => (bool) $value === true);

        return DB::transaction(function () use ($application, $reviewerId, $requirements, $remarks, $allComplete) {
            $application->update([
                'pre_enrolment_form_submitted' => (bool) ($requirements['pre_enrolment_form_submitted'] ?? false),
                'student_health_form_submitted' => (bool) ($requirements['student_health_form_submitted'] ?? false),
                'report_card_submitted' => (bool) ($requirements['report_card_submitted'] ?? false),
                'id_picture_submitted' => (bool) ($requirements['id_picture_submitted'] ?? false),
                'requirements_verified_by' => $reviewerId,
                'requirements_verified_at' => now(),
                'requirements_remarks' => $remarks,
                'workflow_stage' => $allComplete
                    ? Application::WORKFLOW_ENROLLMENT
                    : Application::WORKFLOW_REGISTRAR_REQUIREMENTS,
            ]);

            return $application->fresh(['program', 'requirementsVerifier']);
        });
    }

    /**
     * Create or reuse the student account/profile tied to an application that is ready for enrollment.
     *
     * @throws \Exception
     */
    public function ensureStudentForEnrollment(Application $application): Student
    {
        if (!$application->isInEnrollmentStage() && !$application->isInCashierStage()) {
            throw new \Exception('Only applicants in the enrollment or cashier stage can be converted to student records.');
        }

        return DB::transaction(function () use ($application) {
            $user = User::with('student')->where('email', $application->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $application->fullName(),
                    'email' => $application->email,
                    'password' => 'password',
                    'is_active' => false,
                ]);
            } else {
                $user->update([
                    'name' => $application->fullName(),
                    'is_active' => false,
                ]);
            }

            if ($user->student) {
                $user->student->update([
                    'program_id' => $application->program_applied_id,
                    'year_level' => $application->year_level,
                    'status' => $user->student->status === 'active' ? 'active' : 'admitted',
                    'date_of_birth' => $application->date_of_birth,
                    'gender' => $application->gender,
                    'address' => $application->address,
                    'contact_number' => $application->contact_number,
                    'guardian_name' => $application->guardian_name,
                    'guardian_contact' => $application->guardian_contact,
                    'admission_date' => $user->student->admission_date ?: now(),
                ]);

                return $user->student->fresh(['user', 'program']);
            }

            return Student::create([
                'user_id' => $user->id,
                'student_id' => $this->generateStudentId(),
                'program_id' => $application->program_applied_id,
                'year_level' => $application->year_level,
                'status' => 'admitted',
                'date_of_birth' => $application->date_of_birth,
                'gender' => $application->gender,
                'address' => $application->address,
                'contact_number' => $application->contact_number,
                'guardian_name' => $application->guardian_name,
                'guardian_contact' => $application->guardian_contact,
                'admission_date' => now(),
            ]);
        });
    }

    /**
     * Mark enrollment as processed and forward the record to Cashier.
     *
     * @throws \Exception
     */
    public function processEnrollment(Application $application, mixed $reviewerId, ?string $remarks = null): Application
    {
        if (!$application->isInEnrollmentStage()) {
            throw new \Exception('Only applicants in the enrollment stage can be forwarded to Cashier.');
        }

        if (!$application->hasCompleteRequirements()) {
            throw new \Exception('All required documents must be verified before processing enrollment.');
        }

        $reviewerId = $reviewerId ? (int) $reviewerId : null;

        return DB::transaction(function () use ($application, $reviewerId, $remarks) {
            $application->update([
                'workflow_stage' => Application::WORKFLOW_CASHIER_PAYMENT,
                'account_status' => Application::ACCOUNT_STATUS_PENDING,
                'account_released_at' => null,
                'enrollment_processed_by' => $reviewerId,
                'enrollment_processed_at' => now(),
                'cashier_forwarded_at' => now(),
                'requirements_remarks' => $remarks ?: $application->requirements_remarks,
            ]);

            return $application->fresh(['program', 'enrollmentProcessor']);
        });
    }

    private function generateStudentId(): string
    {
        $year = now()->format('Y');
        $latestStudentId = Student::where('student_id', 'like', $year . '-%')
            ->orderByDesc('student_id')
            ->value('student_id');

        $nextNumber = 1;

        if ($latestStudentId) {
            $parts = explode('-', $latestStudentId);
            $nextNumber = isset($parts[1]) ? ((int) $parts[1]) + 1 : 1;
        }

        return sprintf('%s-%05d', $year, $nextNumber);
    }
}
