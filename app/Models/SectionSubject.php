<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionSubject extends Model
{
    protected $fillable = ['section_id', 'subject_id', 'faculty_id', 'schedule', 'room'];

    // ─── Relationships ────────────────────────────────────────

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Enrollment subjects that match this section + subject combo.
     */
    public function enrollmentSubjects(): HasMany
    {
        return $this->section->enrollmentSubjects()
            ->where('subject_id', $this->subject_id);
    }

    // ─── Helpers ──────────────────────────────────────────────

    /**
     * Count enrolled students for this section-subject.
     */
    public function enrolledCount(): int
    {
        return EnrollmentSubject::where('section_id', $this->section_id)
            ->where('subject_id', $this->subject_id)
            ->whereHas('enrollment', fn ($q) => $q->whereIn('status', ['assessed', 'enrolled']))
            ->count();
    }

    /**
     * Display label: Section Name + Subject Code.
     */
    public function label(): string
    {
        return ($this->section?->name ?? '') . ' — ' . ($this->subject?->code ?? '');
    }
}
