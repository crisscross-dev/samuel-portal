<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSchedule extends Model
{
    protected $fillable = [
        'exam_date',
        'time_slot',
        'max_capacity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'exam_date'    => 'date',
            'is_active'    => 'boolean',
            'max_capacity' => 'integer',
        ];
    }

    /* ─── Relationships ──────────────────────────────── */

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'exam_schedule_id');
    }

    /* ─── Computed Attributes ────────────────────────── */

    public function getBookedCountAttribute(): int
    {
        return $this->applications_count ?? $this->applications()->count();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->max_capacity - $this->bookedCount);
    }

    /** e.g. "Saturday, March 15, 2026 – 9:00 AM" */
    public function getLabelAttribute(): string
    {
        $time = $this->time_slot === '9am' ? '9:00 AM' : '1:00 PM';
        return $this->exam_date->format('l, F j, Y') . ' – ' . $time;
    }

    /** e.g. "Mar 15, 2026 – 9:00 AM" */
    public function getShortLabelAttribute(): string
    {
        $time = $this->time_slot === '9am' ? '9:00 AM' : '1:00 PM';
        return $this->exam_date->format('M j, Y') . ' – ' . $time;
    }

    /* ─── Scopes ─────────────────────────────────────── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithSlots($query)
    {
        return $query->active()
            ->withCount('applications')
            ->orderBy('exam_date')
            ->orderBy('time_slot');
    }
}
