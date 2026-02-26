<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'grade_level_id', 'academic_year_id', 'adviser_id', 'max_students',
    ];

    // ─── Relationships ────────────────────────────────────────

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function adviser(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'adviser_id');
    }

    public function sectionSubjects(): HasMany
    {
        return $this->hasMany(SectionSubject::class);
    }

    public function enrollmentSubjects(): HasMany
    {
        return $this->hasMany(EnrollmentSubject::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    // ─── Computed ─────────────────────────────────────────────

    /**
     * Get the department via grade level.
     */
    public function department(): ?Department
    {
        return $this->gradeLevel?->department;
    }

    /**
     * Count students assigned to this section.
     */
    public function enrolledStudentCount(): int
    {
        return $this->students()->count();
    }

    public function isFull(): bool
    {
        return $this->enrolledStudentCount() >= $this->max_students;
    }

    /**
     * Display label: GradeLevel - SectionName (e.g., "Grade 7 - A").
     */
    public function displayName(): string
    {
        return ($this->gradeLevel?->name ?? '') . ' - ' . $this->name;
    }
}
