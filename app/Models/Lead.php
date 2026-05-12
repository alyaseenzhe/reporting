<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'business',
        'code'
    ];

    protected static function booted(): void
    {
        static::creating(function (Lead $lead): void {
            if (filled($lead->code)) {
                return;
            }

            $lead->code = static::generateNextCode('l');
        });
    }

    public static function generateNextCode(string $prefix): string
    {
        $nextNumber = static::query()
            ->where('code', 'like', $prefix . '%')
            ->pluck('code')
            ->map(function ($code) use ($prefix): int {
                return (int) substr((string) $code, strlen($prefix));
            })
            ->max();

        return $prefix . ((int) $nextNumber + 1);
    }
}
