<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Course;
use App\Models\Document;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;

class DocumentController extends Controller
{
    public function indexByCourse(Request $request, Course $course)
    {
        $docs = $course->documents()->orderBy('sort_order')
            ->paginate($request->integer('per_page', 20));
        return DocumentResource::collection($docs);
    }

    public function indexByVideo(Request $request, Video $video)
    {
        $docs = $video->documents()->orderBy('sort_order')
            ->paginate($request->integer('per_page', 20));
        return DocumentResource::collection($docs);
    }

    public function show(Document $document)
    {
        $document->load(['course','video']);
        return new DocumentResource($document);
    }

    public function store(StoreDocumentRequest $request)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $data = $request->validated();
        $course = null;
        if (!empty($data['video_id'])) {
            $video = Video::findOrFail($data['video_id']);
            $course = $video->course;
        } elseif (!empty($data['course_id'])) {
            $course = Course::findOrFail($data['course_id']);
        }

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $course && $course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $doc = Document::create($data);
        return (new DocumentResource($doc))->response()->setStatusCode(201);
    }

    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher) {
            $course = $document->course;
            if ($request->filled('video_id')) {
                $video = Video::findOrFail((int) $request->input('video_id'));
                $course = $video->course;
            } elseif ($request->filled('course_id')) {
                $course = Course::findOrFail((int) $request->input('course_id'));
            }
            if ($course && $course->instructor_id !== $user->id) {
                abort(403, 'Forbidden');
            }
        }

        $document->fill($request->validated());
        $document->save();
        return new DocumentResource($document->fresh());
    }

    public function destroy(Request $request, Document $document)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher) {
            $course = $document->course ?? ($document->video?->course);
            if ($course && $course->instructor_id !== $user->id) {
                abort(403, 'Forbidden');
            }
        }

        $document->delete();
        return response()->json(['message' => 'Document deleted']);
    }
}
