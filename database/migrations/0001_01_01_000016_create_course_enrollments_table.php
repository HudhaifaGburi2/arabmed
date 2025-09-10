<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->text('review_ar')->nullable();
            $table->text('review_en')->nullable();
            $table->unique(['user_id','course_id'], 'unique_enrollment');
            $table->index(['user_id','progress_percentage'], 'idx_user_progress');
            $table->index(['course_id','rating'], 'idx_course_ratings');
        });
    }
    public function down(): void {
        Schema::dropIfExists('course_enrollments');
    }
};
