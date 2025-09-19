<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();

            // Foreign key relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('topic_id')->nullable()->constrained()->onDelete('set null');

            // Session timing
            $table->integer('planned_duration')->comment('Planned duration in minutes');
            $table->integer('actual_duration')->nullable()->comment('Actual duration in minutes');
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->datetime('completed_at')->nullable();

            // Learning analytics
            $table->integer('focus_score')->nullable()->comment('User-rated focus level 1-10');
            $table->integer('concepts_completed')->default(0)->comment('Number of concepts/topics completed');
            $table->integer('effectiveness_rating')->nullable()->comment('User-rated session effectiveness 1-10');
            $table->integer('break_count')->default(0)->comment('Number of breaks taken');

            // Session metadata
            $table->text('notes')->nullable()->comment('User notes about the session');
            $table->enum('status', ['planned', 'active', 'paused', 'completed', 'abandoned'])->default('planned');
            $table->json('session_data')->nullable()->comment('Additional session metadata');

            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'started_at']);
            $table->index(['subject_id', 'completed_at']);
            $table->index(['status', 'started_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_sessions');
    }
};
