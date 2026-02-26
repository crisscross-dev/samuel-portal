<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $table = 'pricing';
    protected $fillable = ['key', 'value', 'label', 'description'];

    protected function casts(): array
    {
        return ['value' => 'decimal:2'];
    }

    /**
     * Get pricing value by key.
     */
    public static function get(string $key, float $default = 0): float
    {
        return (float) (self::where('key', $key)->first()?->value ?? $default);
    }

    /**
     * Set pricing value by key.
     */
    public static function set(string $key, float $value): self
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
