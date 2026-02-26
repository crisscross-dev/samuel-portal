<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id', 'semester_id', 'tuition_structure_id', 'status', 'total_units',
        'total_amount', 'remarks', 'enrolled_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'enrolled_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function tuitionStructure(): BelongsTo
    {
        return $this->belongsTo(TuitionStructure::class);
    }

    public function enrollmentSubjects(): HasMany
    {
        return $this->hasMany(EnrollmentSubject::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function totalPaid(): float
    {
        return (float) $this->payments()->where('status', 'verified')->sum('amount');
    }

    public function balance(): float
    {
        return (float) $this->total_amount - $this->totalPaid();
    }

    public function isFullyPaid(): bool
    {
        return $this->balance() <= 0;
    }
}
