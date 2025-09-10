<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->text('question_ar');
            $table->text('question_en')->nullable();
            $table->enum('question_type', ['multiple_choice','true_false','short_answer','essay']);
            $table->decimal('marks', 5, 2)->default(1.00);
            $table->text('explanation_ar')->nullable();
            $table->text('explanation_en')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->index(['exam_id','sort_order'], 'idx_exam_order');
            $table->index('question_type', 'idx_type');
        });
    }
    public function down(): void {
        Schema::dropIfExists('questions');
    }
};
