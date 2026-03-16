<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuidanceInterviewSlot extends Model
{
    public const TYPE_JHS = 'jhs';
    public const TYPE_SHS = 'shs';

    protected $fillable = [
        'form_type',
        'interview_date',
        'start_time',
        'end_time',
        'is_active',
        'deactivated_at',
        'deactivation_reason',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'interview_date' => 'date',
            'is_active' => 'boolean',
            'deactivated_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function application(): HasOne
    {
        return $this->hasOne(Application::class, 'interview_slot_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->whereDate('interview_date', '>=', now()->toDateString())
            ->whereDoesntHave('application');
    }
}
