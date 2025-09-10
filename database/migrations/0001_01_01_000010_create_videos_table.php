<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title_ar', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('slug', 255);
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('video_url', 500);
            $table->string('thumbnail_url', 500)->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->decimal('file_size_mb', 10, 2)->default(0);
            $table->enum('video_quality', ['360p','720p','1080p','4k'])->default('720p');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['course_id','slug'], 'unique_course_slug');
            $table->index(['course_id','sort_order'], 'idx_course_order');
            $table->index('is_published', 'idx_published');
            $table->index('is_free', 'idx_free');
        });
    }
    public function down(): void {
        Schema::dropIfExists('videos');
    }
};
