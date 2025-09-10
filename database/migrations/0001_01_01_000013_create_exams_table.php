<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title_ar', 300);
            $table->string('title_en', 300)->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('instructions_ar')->nullable();
            $table->text('instructions_en')->nullable();
            $table->integer('time_limit_minutes')->nullable();
            $table->integer('max_attempts')->default(1);
            $table->decimal('passing_score', 5, 2)->default(70.00);
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->integer('questions_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index('course_id', 'idx_course');
            $table->index('is_active', 'idx_active');
            $table->index(['starts_at','ends_at'], 'idx_schedule');
        });
    }
    public function down(): void {
        Schema::dropIfExists('exams');
    }
};
