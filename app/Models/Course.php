<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'name',
        'description',
        'priority',
        'deadline',
        'estimated_hours',
        'progress_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
            'deadline' => 'datetime',
            'estimated_hours' => 'integer',
            'progress_percentage' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    public function activeTopics(): HasMany
    {
        return $this->hasMany(Topic::class)->where('is_active', true);
    }
}
