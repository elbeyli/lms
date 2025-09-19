<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'description',
        'difficulty',
        'estimated_minutes',
        'prerequisites',
        'progress_percentage',
        'is_completed',
        'is_active',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'difficulty' => 'integer',
            'estimated_minutes' => 'integer',
            'prerequisites' => 'array',
            'progress_percentage' => 'integer',
            'is_completed' => 'boolean',
            'is_active' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'progress_percentage' => 100,
            'completed_at' => now(),
        ]);
    }

    public function updateProgress(int $percentage): void
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'is_completed' => $percentage >= 100,
            'completed_at' => $percentage >= 100 ? now() : null,
        ]);
    }

    public function studySessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudySession::class);
    }
}
