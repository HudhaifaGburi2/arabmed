<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;

class VideoController extends Controller
{
    public function indexByCourse(Request $request, Course $course)
    {
        $videos = $course->videos()->where('is_published', true)
            ->orderBy('sort_order')
            ->paginate($request->integer('per_page', 20));
        return VideoResource::collection($videos);
    }

    public function show(Video $video)
    {
        $video->load(['course']);
        return new VideoResource($video);
    }

    public function store(StoreVideoRequest $request)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $data = $request->validated();
        $course = Course::findOrFail($data['course_id']);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $video = Video::create($data);
        return (new VideoResource($video))->response()->setStatusCode(201);
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher) {
            // Ensure the teacher owns the underlying course
            $course = $video->course;
            if ($request->filled('course_id')) {
                $course = Course::findOrFail((int) $request->input('course_id'));
            }
            if ($course->instructor_id !== $user->id) {
                abort(403, 'Forbidden');
            }
        }

        $video->fill($request->validated());
        $video->save();
        return new VideoResource($video->fresh());
    }

    public function destroy(Request $request, Video $video)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $video->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $video->delete();
        return response()->json(['message' => 'Video deleted']);
    }
}
