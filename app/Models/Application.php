<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Route;

class Application extends Model
{
    public const WORKFLOW_SUBMITTED = 'submitted';
    public const WORKFLOW_EXAM_APPROVED = 'exam_approved';
    public const WORKFLOW_GUIDANCE_REVIEW = 'guidance_review';
    public const WORKFLOW_INTERVIEW_SCHEDULED = 'interview_scheduled';
    public const WORKFLOW_INTERVIEW_FORM_SUBMITTED = 'interview_form_submitted';
    public const WORKFLOW_REGISTRAR_REQUIREMENTS = 'registrar_requirements';
    public const WORKFLOW_ENROLLMENT = 'enrollment';
    public const WORKFLOW_CASHIER_PAYMENT = 'cashier_payment';
    public const WORKFLOW_ARCHIVED = 'archived';
    public const WORKFLOW_EXAM_FAILED = 'exam_failed';
    public const WORKFLOW_REJECTED = 'rejected';

    public const EXAM_RESULT_PASSED = 'passed';
    public const EXAM_RESULT_FAILED = 'failed';
    public const INTERVIEW_RESULT_PASSED = 'passed';
    public const INTERVIEW_RESULT_FAILED = 'failed';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'lrn',
        'email',
        'contact_number',
        'date_of_birth',
        'gender',
        'nationality',
        'religion',
        'address',
        'program_applied_id',
        'year_level',
        'guardian_name',
        'guardian_contact',
        'guardian_relationship',
        'elementary_school',
        'document_path',
        'status',
        'workflow_stage',
        'exam_result',
        'payment_status',
        'is_active',
        'app_id',
        'exam_schedule',
        'exam_schedule_id',
        'remarks',
        'exam_remarks',
        'guidance_remarks',
        'reviewed_by',
        'reviewed_at',
        'exam_result_recorded_by',
        'exam_result_recorded_at',
        'forwarded_to_guidance_at',
        'interview_date',
        'guidance_user_id',
        'interview_form_token',
        'interview_form_sent_at',
        'interview_form_submitted_at',
        'interview_result',
        'interview_remarks',
        'interview_evaluated_by',
        'interview_evaluated_at',
        'returned_to_registrar_at',
        'is_archived',
        'archived_at',
        'archive_reason',
        'pre_enrolment_form_submitted',
        'student_health_form_submitted',
        'report_card_submitted',
        'id_picture_submitted',
        'requirements_remarks',
        'requirements_verified_by',
        'requirements_verified_at',
        'enrollment_processed_by',
        'enrollment_processed_at',
        'cashier_forwarded_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'reviewed_at'   => 'datetime',
            'exam_result_recorded_at' => 'datetime',
            'forwarded_to_guidance_at' => 'datetime',
            'interview_date' => 'date',
            'interview_form_sent_at' => 'datetime',
            'interview_form_submitted_at' => 'datetime',
            'interview_evaluated_at' => 'datetime',
            'returned_to_registrar_at' => 'datetime',
            'archived_at' => 'datetime',
            'requirements_verified_at' => 'datetime',
            'enrollment_processed_at' => 'datetime',
            'cashier_forwarded_at' => 'datetime',
            'is_active' => 'boolean',
            'is_archived' => 'boolean',
            'pre_enrolment_form_submitted' => 'boolean',
            'student_health_form_submitted' => 'boolean',
            'report_card_submitted' => 'boolean',
            'id_picture_submitted' => 'boolean',
        ];
    }

    /* ─── Relationships ──────────────────────────────── */

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_applied_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function examReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exam_result_recorded_by');
    }

    public function guidanceUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guidance_user_id');
    }

    public function interviewEvaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interview_evaluated_by');
    }

    public function requirementsVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requirements_verified_by');
    }

    public function enrollmentProcessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enrollment_processed_by');
    }

    public function admissionPayment(): HasOne
    {
        return $this->hasOne(AdmissionPayment::class);
    }

    public function examSchedule(): BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    /* ─── Scopes ─────────────────────────────────────── */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeWorkflowStage($query, string $stage)
    {
        return $query->where('workflow_stage', $stage);
    }

    public function scopeActiveRecords($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /* ─── Helpers ────────────────────────────────────── */

    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isActiveRecord(): bool
    {
        return $this->is_active;
    }

    public function isExamApproved(): bool
    {
        return $this->workflow_stage === self::WORKFLOW_EXAM_APPROVED;
    }

    public function isForwardedToGuidance(): bool
    {
        return $this->workflow_stage === self::WORKFLOW_GUIDANCE_REVIEW;
    }

    public function hasInterviewScheduled(): bool
    {
        return $this->workflow_stage === self::WORKFLOW_INTERVIEW_SCHEDULED && $this->interview_date !== null;
    }

    public function hasSubmittedInterviewForm(): bool
    {
        return $this->workflow_stage === self::WORKFLOW_INTERVIEW_FORM_SUBMITTED;
    }

    public function isReadyForInterviewEvaluation(): bool
    {
        return in_array($this->workflow_stage, [
            self::WORKFLOW_INTERVIEW_SCHEDULED,
            self::WORKFLOW_INTERVIEW_FORM_SUBMITTED,
        ], true) && !$this->is_archived;
    }

    public function isInRegistrarRequirements(): bool
    {
        return $this->workflow_stage === self::WORKFLOW_REGISTRAR_REQUIREMENTS;
    }

    public function isInEnrollmentStage(): bool
    {
        return $this->workflow_stage === self::WORKFLOW_ENROLLMENT;
    }

    public function isInCashierStage(): bool
    {
        return $this->workflow_stage === self::WORKFLOW_CASHIER_PAYMENT;
    }

    public function isArchivedRecord(): bool
    {
        return $this->is_archived || $this->workflow_stage === self::WORKFLOW_ARCHIVED;
    }

    public function canScheduleInterview(): bool
    {
        return in_array($this->workflow_stage, [
            self::WORKFLOW_GUIDANCE_REVIEW,
            self::WORKFLOW_INTERVIEW_SCHEDULED,
        ], true) && $this->isActiveRecord();
    }

    public function workflowLabel(): string
    {
        return match ($this->workflow_stage) {
            self::WORKFLOW_EXAM_APPROVED => 'Approved for Entrance Exam',
            self::WORKFLOW_GUIDANCE_REVIEW => 'Forwarded to Guidance',
            self::WORKFLOW_INTERVIEW_SCHEDULED => 'Interview Scheduled',
            self::WORKFLOW_INTERVIEW_FORM_SUBMITTED => 'Interview Form Submitted',
            self::WORKFLOW_REGISTRAR_REQUIREMENTS => 'Registrar Requirements Verification',
            self::WORKFLOW_ENROLLMENT => 'Enrollment Stage',
            self::WORKFLOW_CASHIER_PAYMENT => 'Forwarded to Cashier',
            self::WORKFLOW_ARCHIVED => 'Archived',
            self::WORKFLOW_EXAM_FAILED => 'Exam Failed',
            self::WORKFLOW_REJECTED => 'Rejected',
            default => 'Pending Review',
        };
    }

    public function workflowBadgeClass(): string
    {
        return match ($this->workflow_stage) {
            self::WORKFLOW_EXAM_APPROVED => 'info',
            self::WORKFLOW_GUIDANCE_REVIEW => 'primary',
            self::WORKFLOW_INTERVIEW_SCHEDULED => 'warning text-dark',
            self::WORKFLOW_INTERVIEW_FORM_SUBMITTED => 'success',
            self::WORKFLOW_REGISTRAR_REQUIREMENTS => 'primary',
            self::WORKFLOW_ENROLLMENT => 'info',
            self::WORKFLOW_CASHIER_PAYMENT => 'dark',
            self::WORKFLOW_ARCHIVED => 'secondary',
            self::WORKFLOW_EXAM_FAILED,
            self::WORKFLOW_REJECTED => 'danger',
            default => 'secondary',
        };
    }

    public function examResultLabel(): string
    {
        return match ($this->exam_result) {
            self::EXAM_RESULT_PASSED => 'Passed',
            self::EXAM_RESULT_FAILED => 'Failed',
            default => 'Pending',
        };
    }

    public function interviewFormUrl(): ?string
    {
        if (!$this->interview_form_token || !Route::has('admission.interview-form.show')) {
            return null;
        }

        return route('admission.interview-form.show', $this->interview_form_token);
    }

    public function interviewResultLabel(): string
    {
        return match ($this->interview_result) {
            self::INTERVIEW_RESULT_PASSED => 'Passed',
            self::INTERVIEW_RESULT_FAILED => 'Failed',
            default => 'Pending',
        };
    }

    public function requirementsChecklist(): array
    {
        return [
            'pre_enrolment_form_submitted' => 'Accomplished Pre-Enrolment Form',
            'student_health_form_submitted' => "Accomplished Student Health Form (with Parent/Guardian's Signature)",
            'report_card_submitted' => 'Original Report Card (SF9)',
            'id_picture_submitted' => '1x1 ID Picture',
        ];
    }

    public function hasCompleteRequirements(): bool
    {
        return $this->pre_enrolment_form_submitted
            && $this->student_health_form_submitted
            && $this->report_card_submitted
            && $this->id_picture_submitted;
    }

    /**
     * Human-readable exam schedule label (new relationship or legacy string).
     */
    public function examLabel(): string
    {
        if ($this->examSchedule) {
            $time = $this->examSchedule->time_slot === '9am' ? '9:00 AM' : '1:00 PM';
            return $this->examSchedule->exam_date->format('l, F j, Y') . ' – ' . $time;
        }
        if ($this->exam_schedule === 'saturday_9am') return 'Saturday – 9:00 AM';
        if ($this->exam_schedule === 'saturday_1pm') return 'Saturday – 1:00 PM';
        return 'Not yet selected';
    }

    public function hasPaymentSubmitted(): bool
    {
        return $this->admissionPayment !== null;
    }

    public function isPaymentPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}
