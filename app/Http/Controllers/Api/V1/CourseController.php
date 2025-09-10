<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()->with(['instructor','category'])
            ->when($request->boolean('published', true), fn($q) => $q->where('is_published', true))
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->integer('category_id')))
            ->orderByDesc('published_at');

        $courses = $query->paginate($request->integer('per_page', 12));
        return CourseResource::collection($courses);
    }

    public function show(Course $course)
    {
        $course->load(['instructor','category','videos','documents','exams']);
        return new CourseResource($course);
    }

    public function store(StoreCourseRequest $request)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $data = $request->validated();
        // If teacher, force instructor_id to current user
        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher) {
            $data['instructor_id'] = $user->id;
        } else {
            // admin can set instructor_id; default to self if not provided
            $data['instructor_id'] = $data['instructor_id'] ?? $user->id;
        }

        $course = Course::create($data);
        $course->refresh()->load(['instructor','category']);
        return (new CourseResource($course))->response()->setStatusCode(201);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isAdmin = $user->roles()->where('name', 'admin')->exists();
        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $data = $request->validated();
        if ($isTeacher) {
            // Prevent teachers from reassigning instructor
            unset($data['instructor_id']);
        }

        $course->fill($data);
        $course->save();
        return new CourseResource($course->fresh(['instructor','category']));
    }

    public function destroy(Request $request, Course $course)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted']);
    }

    public function enroll(Request $request, Course $course)
    {
        $user = $request->user();
        abort_unless($user, 401, 'Unauthorized');

        $enrollment = CourseEnrollment::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'enrolled_at' => now(),
                'progress_percentage' => 0,
            ]
        );

        // Optionally increment enrollments_count the first time
        if ($enrollment->wasRecentlyCreated) {
            $course->increment('enrollments_count');
        }

        return response()->json([
            'message' => 'Enrolled successfully',
            'course' => new CourseResource($course->fresh(['instructor','category'])),
        ], 201);
    }
}
