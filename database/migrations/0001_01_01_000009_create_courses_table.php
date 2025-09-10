<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title_ar', 300);
            $table->string('title_en', 300)->nullable();
            $table->string('slug', 255)->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('short_description_ar', 500)->nullable();
            $table->string('short_description_en', 500)->nullable();
            $table->string('thumbnail_url', 500)->nullable();
            $table->string('cover_image_url', 500)->nullable();
            $table->string('trailer_video_url', 500)->nullable();
            $table->foreignId('instructor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->enum('level', ['beginner','intermediate','advanced'])->default('beginner');
            $table->integer('duration_minutes')->default(0);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('enrollments_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0.00);
            $table->integer('ratings_count')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index('instructor_id', 'idx_instructor');
            $table->index('category_id', 'idx_category');
            $table->index(['is_published','published_at'], 'idx_published');
            $table->index(['is_free','price'], 'idx_price');
        });
    }
    public function down(): void {
        Schema::dropIfExists('courses');
    }
};
