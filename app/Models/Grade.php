<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'enrollment_subject_id',
        'student_id',
        'faculty_id',
        'final_grade',
        'remarks',
        'is_finalized',
        'finalized_at',
    ];

    protected function casts(): array
    {
        return [
            'final_grade'  => 'decimal:2',
            'is_finalized' => 'boolean',
            'finalized_at' => 'datetime',
        ];
    }

    /* ───── Relationships ───── */

    public function enrollmentSubject(): BelongsTo
    {
        return $this->belongsTo(EnrollmentSubject::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(GradeAuditLog::class)->orderByDesc('performed_at');
    }

    /* ───── Scopes ───── */

    /** Only finalized grades (for student viewing). */
    public function scopeFinalized($query)
    {
        return $query->where('is_finalized', true);
    }

    /** Only draft (editable) grades. */
    public function scopeDraft($query)
    {
        return $query->where('is_finalized', false);
    }

    /** Grades belonging to a specific faculty. */
    public function scopeForFaculty($query, int $facultyId)
    {
        return $query->where('faculty_id', $facultyId);
    }

    /* ───── Business Logic ───── */

    /**
     * Compute remarks automatically from final_grade.
     * >= 75 → Passed | < 75 → Failed | null → Incomplete
     */
    public static function computeRemarks(?float $grade): string
    {
        if ($grade === null) {
            return 'incomplete';
        }

        return $grade >= 75 ? 'passed' : 'failed';
    }

    /**
     * Set the final grade and auto-compute remarks.
     */
    public function setFinalGrade(?float $grade): self
    {
        $this->final_grade = $grade;
        $this->remarks     = self::computeRemarks($grade);
        return $this;
    }

    /**
     * Finalize this grade — locks it from further editing.
     */
    public function finalize(): void
    {
        $this->remarks      = self::computeRemarks((float) $this->final_grade);
        $this->is_finalized = true;
        $this->finalized_at = now();
        $this->save();
    }

    /**
     * Reopen a finalized grade (admin/registrar only).
     */
    public function reopen(): void
    {
        $this->is_finalized = false;
        $this->finalized_at = null;
        $this->save();
    }

    /**
     * Check if this grade can be edited.
     */
    public function isEditable(): bool
    {
        return !$this->is_finalized;
    }
}
