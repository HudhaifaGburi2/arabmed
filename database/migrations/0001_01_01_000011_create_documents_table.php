<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->foreignId('video_id')->nullable()->constrained('videos')->cascadeOnDelete();
            $table->string('title_ar', 300);
            $table->string('title_en', 300)->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('file_url', 500);
            $table->enum('file_type', ['pdf','doc','docx','ppt','pptx','txt','image']);
            $table->decimal('file_size_mb', 10, 2)->default(0);
            $table->string('thumbnail_url', 500)->nullable();
            $table->integer('downloads_count')->default(0);
            $table->boolean('is_downloadable')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index('course_id', 'idx_course');
            $table->index('video_id', 'idx_video');
            $table->index('file_type', 'idx_type');
        });
    }
    public function down(): void {
        Schema::dropIfExists('documents');
    }
};
