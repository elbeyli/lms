<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'course_id',
        'topic_id',
        'planned_duration',
        'actual_duration',
        'started_at',
        'ended_at',
        'completed_at',
        'focus_score',
        'concepts_completed',
        'effectiveness_rating',
        'break_count',
        'notes',
        'status',
        'session_data',
    ];

    protected function casts(): array
    {
        return [
            'planned_duration' => 'integer',
            'actual_duration' => 'integer',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'completed_at' => 'datetime',
            'focus_score' => 'integer',
            'concepts_completed' => 'integer',
            'effectiveness_rating' => 'integer',
            'break_count' => 'integer',
            'session_data' => 'array',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    // Session status scopes
    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['active', 'paused']);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPaused(): bool
    {
        return $this->status === 'paused';
    }

    public function getDurationDifferenceAttribute(): ?int
    {
        if ($this->actual_duration === null || $this->planned_duration === null) {
            return null;
        }

        return $this->actual_duration - $this->planned_duration;
    }

    public function getEfficiencyRateAttribute(): ?float
    {
        if ($this->actual_duration === null || $this->planned_duration === null || $this->actual_duration === 0) {
            return null;
        }

        return round(($this->planned_duration / $this->actual_duration) * 100, 1);
    }
}
