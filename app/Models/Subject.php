<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'description', 'lecture_units', 'lab_units', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function totalUnits(): int
    {
        return $this->lecture_units + $this->lab_units;
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_subject')
                    ->withPivot('year_level', 'semester')
                    ->withTimestamps();
    }

    public function sectionSubjects(): HasMany
    {
        return $this->hasMany(SectionSubject::class);
    }

    public function enrollmentSubjects(): HasMany
    {
        return $this->hasMany(EnrollmentSubject::class);
    }
}
