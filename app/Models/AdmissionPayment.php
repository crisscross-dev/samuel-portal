<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionPayment extends Model
{
    protected $fillable = [
        'application_id',
        'reference_number',
        'receipt_image',
        'payment_status',
        'submitted_at',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'verified_at'  => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isVerified(): bool
    {
        return $this->payment_status === 'paid' && $this->verified_at !== null;
    }
}
