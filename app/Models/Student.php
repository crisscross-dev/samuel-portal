<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    /**
     * Valid student statuses reflecting the institutional lifecycle:
     *
     *  applicant  → Legacy (pre-admission module)
     *  admitted   → Approved application, account created, not yet enrolled
     *  active     → Successfully enrolled in at least one semester
     *  inactive   → Not enrolled in the current semester
     *  suspended  → Administratively suspended
     *  graduated  → Completed program
     *  dropped    → Dropped out
     */
    public const STATUSES = [
        'applicant', 'admitted', 'active', 'inactive',
        'suspended', 'graduated', 'dropped',
    ];

    /** Statuses eligible for enrollment. */
    public const ENROLLABLE_STATUSES = ['admitted', 'active'];

    protected $fillable = [
        'user_id', 'student_id', 'program_id', 'year_level', 'section_id',
        'status', 'date_of_birth', 'gender', 'address', 'contact_number',
        'guardian_name', 'guardian_contact', 'admission_date',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
        ];
    }

    /* ─── Relationships ──────────────────────────────── */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /* ─── Scopes ─────────────────────────────────────── */

    public function scopeEnrollable($query)
    {
        return $query->whereIn('status', self::ENROLLABLE_STATUSES);
    }

    public function scopeAdmitted($query)
    {
        return $query->where('status', 'admitted');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /* ─── Lifecycle Helpers ──────────────────────────── */

    public function isEnrollable(): bool
    {
        return in_array($this->status, self::ENROLLABLE_STATUSES);
    }

    public function isAdmitted(): bool
    {
        return $this->status === 'admitted';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    public function currentEnrollment(): ?Enrollment
    {
        $semester = Semester::current();
        if (!$semester) return null;

        return $this->enrollments()->where('semester_id', $semester->id)->first();
    }
}
