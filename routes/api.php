<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\VideoController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\ExamController;
use App\Http\Controllers\Api\V1\ProgressController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\ExamAttemptController;
use App\Http\Controllers\Api\V1\Admin\AdminController;

Route::prefix('v1')->middleware(['localize'])->group(function () {
    // Public auth endpoints
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [PasswordResetController::class, 'resetPassword']);

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}', [CourseController::class, 'show']);

    Route::get('/courses/{course}/videos', [VideoController::class, 'indexByCourse']);
    Route::get('/videos/{video}', [VideoController::class, 'show']);

    Route::get('/courses/{course}/documents', [DocumentController::class, 'indexByCourse']);
    Route::get('/videos/{video}/documents', [DocumentController::class, 'indexByVideo']);
    Route::get('/documents/{document}', [DocumentController::class, 'show']);

    Route::get('/courses/{course}/exams', [ExamController::class, 'indexByCourse']);
    Route::get('/exams/{exam}', [ExamController::class, 'show']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        // Authoring (admin, teacher)
        Route::middleware('role:admin,teacher')->group(function () {
            // Courses
            Route::post('/courses', [CourseController::class, 'store']);
            Route::put('/courses/{course}', [CourseController::class, 'update']);
            Route::delete('/courses/{course}', [CourseController::class, 'destroy']);

            // Videos
            Route::post('/videos', [VideoController::class, 'store']);
            Route::put('/videos/{video}', [VideoController::class, 'update']);
            Route::delete('/videos/{video}', [VideoController::class, 'destroy']);

            // Documents
            Route::post('/documents', [DocumentController::class, 'store']);
            Route::put('/documents/{document}', [DocumentController::class, 'update']);
            Route::delete('/documents/{document}', [DocumentController::class, 'destroy']);

            // Exams
            Route::post('/exams', [ExamController::class, 'store']);
            Route::put('/exams/{exam}', [ExamController::class, 'update']);
            Route::delete('/exams/{exam}', [ExamController::class, 'destroy']);

            // Questions
            Route::post('/exams/{exam}/questions', [ExamController::class, 'storeQuestion']);
            Route::put('/exams/{exam}/questions/{question}', [ExamController::class, 'updateQuestion']);
            Route::delete('/exams/{exam}/questions/{question}', [ExamController::class, 'destroyQuestion']);

            // Options
            Route::post('/exams/{exam}/questions/{question}/options', [ExamController::class, 'storeOption']);
            Route::put('/exams/{exam}/questions/{question}/options/{option}', [ExamController::class, 'updateOption']);
            Route::delete('/exams/{exam}/questions/{question}/options/{option}', [ExamController::class, 'destroyOption']);
        });

        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::put('/auth/profile', [ProfileController::class, 'update']);
        Route::put('/auth/password', [ProfileController::class, 'changePassword']);

        // Admin-only APIs
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/stats', [AdminController::class, 'stats']);
            Route::get('/admin/users', [AdminController::class, 'users']);
        });

        // Enroll in a course
        Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);

        // Progress endpoints (user-scoped)
        Route::get('/users/{user}/progress/videos', [ProgressController::class, 'videoProgress']);
        Route::get('/users/{user}/progress/audios', [ProgressController::class, 'audioProgress']);

        // Update progress
        Route::put('/videos/{video}/progress', [ProgressController::class, 'updateVideoProgress']);
        Route::put('/audios/{audio}/progress', [ProgressController::class, 'updateAudioProgress']);

        // Exam attempts
        Route::post('/exams/{exam}/attempts/start', [ExamAttemptController::class, 'start']);
        Route::post('/exams/{exam}/attempts/submit', [ExamAttemptController::class, 'submit']);
    });
});
