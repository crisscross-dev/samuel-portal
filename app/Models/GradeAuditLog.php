<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeAuditLog extends Model
{
    protected $fillable = [
        'grade_id', 'user_id', 'action',
        'old_grade', 'new_grade',
        'old_remarks', 'new_remarks',
        'notes', 'ip_address', 'performed_at',
    ];

    protected function casts(): array
    {
        return [
            'old_grade'    => 'decimal:2',
            'new_grade'    => 'decimal:2',
            'performed_at' => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────

    /**
     * Record an audit event for a grade change.
     */
    public static function log(
        Grade  $grade,
        int    $userId,
        string $action,
        ?float $oldGrade = null,
        ?float $newGrade = null,
        ?string $oldRemarks = null,
        ?string $newRemarks = null,
        ?string $notes = null,
    ): self {
        return static::create([
            'grade_id'     => $grade->id,
            'user_id'      => $userId,
            'action'       => $action,
            'old_grade'    => $oldGrade,
            'new_grade'    => $newGrade,
            'old_remarks'  => $oldRemarks,
            'new_remarks'  => $newRemarks,
            'notes'        => $notes,
            'ip_address'   => request()?->ip(),
            'performed_at' => now(),
        ]);
    }

    /**
     * Action label for display.
     */
    public function actionLabel(): string
    {
        return match ($this->action) {
            'created'   => 'Grade Created',
            'updated'   => 'Grade Updated',
            'finalized' => 'Grade Finalized',
            'reopened'  => 'Grade Reopened',
            'imported'  => 'Bulk Imported',
            default     => ucfirst($this->action),
        };
    }

    /**
     * Badge color for action.
     */
    public function actionBadge(): string
    {
        return match ($this->action) {
            'created'   => 'info',
            'updated'   => 'primary',
            'finalized' => 'success',
            'reopened'  => 'warning',
            'imported'  => 'secondary',
            default     => 'dark',
        };
    }
}
