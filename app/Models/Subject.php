<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'color',
        'difficulty_base',
        'total_hours_estimated',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'difficulty_base' => 'integer',
            'total_hours_estimated' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function activeCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('is_active', true);
    }
}
