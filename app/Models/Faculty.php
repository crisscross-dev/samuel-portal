<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use SoftDeletes;

    protected $table = 'faculty';

    protected $fillable = [
        'user_id', 'employee_id', 'department_id', 'specialization',
        'contact_number', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    // ─── Relationships ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function advisedSections(): HasMany
    {
        return $this->hasMany(Section::class, 'adviser_id');
    }

    public function sectionSubjects(): HasMany
    {
        return $this->hasMany(SectionSubject::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function headedDepartment()
    {
        return $this->hasOne(Department::class, 'head_faculty_id');
    }

    // ─── Teaching Loads ───────────────────────────────────────

    /**
     * Get current teaching loads (section-subjects for the active AY).
     */
    /**
     * Check if this faculty member is a department head.
     */
    public function isDepartmentHead(): bool
    {
        return Department::where('head_faculty_id', $this->id)->exists();
    }

    /**
     * Get the department this faculty member heads (if any).
     */
    public function getHeadedDepartmentCached(): ?Department
    {
        return $this->headedDepartment;
    }

    public function currentTeachingLoads()
    {
        $ay = AcademicYear::where('is_active', true)->first();

        return $this->sectionSubjects()
            ->whereHas('section', fn ($q) => $q->where('academic_year_id', $ay?->id))
            ->with(['section.gradeLevel.department', 'subject']);
    }
}
