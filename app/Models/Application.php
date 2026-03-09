<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
