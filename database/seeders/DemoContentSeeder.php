<?php

namespace Database\Seeders;

use App\Models\{User, Category, Course, Video, Document, Exam, Question, QuestionOption};
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a teacher user exists
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'uuid' => (string) Str::uuid(),
                'first_name' => 'Demo',
                'last_name' => 'Teacher',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        if ($role = Role::where('name', 'teacher')->first()) {
            if (!$teacher->roles()->where('roles.id', $role->id)->exists()) {
                $teacher->roles()->attach($role->id, ['assigned_at' => now()]);
            }
        }

        // Category
        $category = Category::firstOrCreate(
            ['slug' => 'general-medicine'],
            [
                'name_ar' => 'الطب العام',
                'name_en' => 'General Medicine',
                'description_ar' => 'مواد الطب العام',
                'description_en' => 'General Medicine materials',
                'is_active' => true,
            ]
        );

        // Course
        $course = Course::firstOrCreate(
            ['slug' => 'intro-to-anatomy'],
            [
                'uuid' => (string) Str::uuid(),
                'title_ar' => 'مقدمة في علم التشريح',
                'title_en' => 'Introduction to Anatomy',
                'description_ar' => 'أساسيات علم التشريح.',
                'description_en' => 'Basics of human anatomy.',
                'short_description_ar' => 'تعلم أساسيات التشريح.',
                'short_description_en' => 'Learn anatomy basics.',
                'instructor_id' => $teacher->id,
                'category_id' => $category->id,
                'level' => 'beginner',
                'is_free' => true,
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        // Videos
        $video1 = Video::firstOrCreate(
            ['course_id' => $course->id, 'slug' => 'skeletal-system'],
            [
                'uuid' => (string) Str::uuid(),
                'title_ar' => 'الجهاز الهيكلي',
                'title_en' => 'Skeletal System',
                'video_url' => 'https://example.com/videos/skeletal.mp4',
                'duration_seconds' => 600,
                'is_published' => true,
            ]
        );
        $video2 = Video::firstOrCreate(
            ['course_id' => $course->id, 'slug' => 'muscular-system'],
            [
                'uuid' => (string) Str::uuid(),
                'title_ar' => 'الجهاز العضلي',
                'title_en' => 'Muscular System',
                'video_url' => 'https://example.com/videos/muscular.mp4',
                'duration_seconds' => 720,
                'is_published' => true,
                'sort_order' => 2,
            ]
        );

        // Documents
        Document::firstOrCreate(
            ['uuid' => '11111111-1111-1111-1111-111111111111'],
            [
                'course_id' => $course->id,
                'title_ar' => 'ملخص الجهاز الهيكلي',
                'title_en' => 'Skeletal Summary',
                'file_url' => 'https://example.com/docs/skeletal.pdf',
                'file_type' => 'pdf',
                'file_size_mb' => 2.5,
                'is_downloadable' => true,
            ]
        );

        // Exam with questions
        $exam = Exam::firstOrCreate(
            ['uuid' => '22222222-2222-2222-2222-222222222222'],
            [
                'course_id' => $course->id,
                'title_ar' => 'اختبار التشريح 1',
                'title_en' => 'Anatomy Quiz 1',
                'time_limit_minutes' => 15,
                'max_attempts' => 3,
                'passing_score' => 70,
                'total_marks' => 2,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonth(),
            ]
        );

        $q1 = Question::firstOrCreate(
            ['uuid' => '33333333-3333-3333-3333-333333333333'],
            [
                'exam_id' => $exam->id,
                'question_ar' => 'كم عدد عظام جسم الإنسان البالغ؟',
                'question_en' => 'How many bones are in the adult human body?',
                'question_type' => 'multiple_choice',
                'marks' => 1,
                'sort_order' => 1,
            ]
        );
        QuestionOption::firstOrCreate(['question_id' => $q1->id, 'option_ar' => '206'], ['option_en' => '206', 'is_correct' => true]);
        QuestionOption::firstOrCreate(['question_id' => $q1->id, 'option_ar' => '201'], ['option_en' => '201']);

        $q2 = Question::firstOrCreate(
            ['uuid' => '44444444-4444-4444-4444-444444444444'],
            [
                'exam_id' => $exam->id,
                'question_ar' => 'القلب عضلة.',
                'question_en' => 'The heart is a muscle.',
                'question_type' => 'true_false',
                'marks' => 1,
                'sort_order' => 2,
            ]
        );
        QuestionOption::firstOrCreate(['question_id' => $q2->id, 'option_ar' => 'صحيح'], ['option_en' => 'True', 'is_correct' => true]);
        QuestionOption::firstOrCreate(['question_id' => $q2->id, 'option_ar' => 'خطأ'], ['option_en' => 'False']);
    }
}
