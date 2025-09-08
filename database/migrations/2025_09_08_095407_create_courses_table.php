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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('priority')->default(5);
            $table->datetime('deadline')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['subject_id', 'is_active']);
            $table->index(['subject_id', 'priority']);
            $table->index('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
