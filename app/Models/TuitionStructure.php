<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TuitionStructure extends Model
{
    protected $fillable = [
        'department_id', 'academic_year_id', 'pricing_type',
        'grade_level_id', 'program_id',
        'flat_amount', 'lecture_rate', 'lab_rate',
        'misc_fee', 'reg_fee', 'label', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'    => 'boolean',
            'flat_amount'  => 'decimal:2',
            'lecture_rate' => 'decimal:2',
            'lab_rate'     => 'decimal:2',
            'misc_fee'     => 'decimal:2',
            'reg_fee'      => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ─────────────────────────────────────────────

    /**
     * Find the active tuition structure for a department + academic year.
     */
    public static function findActive(int $departmentId, int $academicYearId): ?self
    {
        return self::where('department_id', $departmentId)
            ->where('academic_year_id', $academicYearId)
            ->where('is_active', true)
            ->latest()
            ->first();
    }

    /**
     * Compute total tuition for a given set of enrollment subjects.
     * Subjects only used for per_unit pricing type.
     */
    public function computeTotal(iterable $enrollmentSubjects = []): float
    {
        if ($this->pricing_type === 'flat') {
            return (float) $this->flat_amount + (float) $this->misc_fee + (float) $this->reg_fee;
        }

        $tuition = 0.0;
        foreach ($enrollmentSubjects as $es) {
            $tuition += $es->subject->lecture_units * (float) $this->lecture_rate;
            $tuition += $es->subject->lab_units * (float) $this->lab_rate;
        }

        return $tuition + (float) $this->misc_fee + (float) $this->reg_fee;
    }

    /**
     * Human-readable label or auto label.
     */
    public function getDisplayLabelAttribute(): string
    {
        return $this->label
            ?: ($this->department->name ?? '?') . ' — ' . ($this->academicYear->name ?? '?');
    }
}
