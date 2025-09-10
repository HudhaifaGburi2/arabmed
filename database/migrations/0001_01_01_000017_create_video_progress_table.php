<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('video_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('video_id')->constrained('videos')->cascadeOnDelete();
            $table->integer('watched_seconds')->default(0);
            $table->integer('total_seconds')->default(0);
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->timestamp('completed_at')->nullable();
            $table->integer('last_position_seconds')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['user_id','video_id'], 'unique_user_video');
        });
    }
    public function down(): void {
        Schema::dropIfExists('video_progress');
    }
};
