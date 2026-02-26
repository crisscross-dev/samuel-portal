<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name', 'code', 'description', 'is_active', 'head_faculty_id'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    // ─── Relationships ────────────────────────────────────────

    public function gradeLevels(): HasMany
    {
        return $this->hasMany(GradeLevel::class)->orderBy('level_order');
    }

    public function faculty(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    public function headFaculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'head_faculty_id');
    }

    // ─── Scopes ───────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ──────────────────────────────────────────────

    /**
     * Check if a given faculty member is the head of this department.
     */
    public function isHeadedBy(Faculty $faculty): bool
    {
        return $this->head_faculty_id === $faculty->id;
    }

    /**
     * Check if this department has a head assigned.
     */
    public function hasHead(): bool
    {
        return $this->head_faculty_id !== null;
    }
}
