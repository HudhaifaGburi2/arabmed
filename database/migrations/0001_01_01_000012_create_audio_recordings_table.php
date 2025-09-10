<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('audio_recordings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->foreignId('video_id')->nullable()->constrained('videos')->cascadeOnDelete();
            $table->string('title_ar', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('slug', 255);
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('audio_url', 500);
            $table->integer('duration_seconds')->default(0);
            $table->decimal('file_size_mb', 10, 2)->default(0);
            $table->enum('format', ['mp3','wav','aac','ogg'])->default('mp3');
            $table->integer('bitrate_kbps')->nullable();
            $table->string('thumbnail_url', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(false);
            $table->integer('plays_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['course_id','slug'], 'unique_course_slug');
            $table->index(['course_id','sort_order'], 'idx_course_order');
            $table->index('is_published', 'idx_published');
            $table->index('is_free', 'idx_free');
        });
    }
    public function down(): void {
        Schema::dropIfExists('audio_recordings');
    }
};
