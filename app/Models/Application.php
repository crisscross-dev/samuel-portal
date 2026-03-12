<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
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
        'payment_status',
        'app_id',
        'exam_schedule',
        'exam_schedule_id',
        'remarks',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'reviewed_at'   => 'datetime',
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
