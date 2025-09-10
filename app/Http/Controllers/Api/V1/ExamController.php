<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamResource;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;
use App\Http\Requests\Exam\StoreQuestionRequest;
use App\Http\Requests\Exam\UpdateQuestionRequest;
use App\Http\Requests\Exam\StoreQuestionOptionRequest;
use App\Http\Requests\Exam\UpdateQuestionOptionRequest;

class ExamController extends Controller
{
    public function indexByCourse(Request $request, Course $course)
    {
        $exams = $course->exams()->where('is_active', true)
            ->orderByDesc('starts_at')
            ->paginate($request->integer('per_page', 10));
        return ExamResource::collection($exams);
    }

    public function show(Exam $exam)
    {
        $exam->load(['course','questions.options']);
        return new ExamResource($exam);
    }

    // Exams CRUD (authoring)
    public function store(StoreExamRequest $request)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $data = $request->validated();
        $course = Course::findOrFail($data['course_id']);
        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $exam = Exam::create($data + ['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        return (new ExamResource($exam->fresh(['course'])))->response()->setStatusCode(201);
    }

    public function update(UpdateExamRequest $request, Exam $exam)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $exam->fill($request->validated());
        $exam->save();
        return new ExamResource($exam->fresh(['course']));
    }

    public function destroy(Request $request, Exam $exam)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $exam->delete();
        return response()->json(['message' => 'Exam deleted']);
    }

    // Questions CRUD
    public function storeQuestion(StoreQuestionRequest $request, Exam $exam)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $data = $request->validated();
        $question = $exam->questions()->create($data + ['uuid' => (string) \Illuminate\Support\Str::uuid()]);
        $exam->increment('questions_count');
        return response()->json([
            'message' => 'Question created',
            'question' => [
                'id' => $question->id,
                'uuid' => $question->uuid,
            ],
        ], 201);
    }

    public function updateQuestion(UpdateQuestionRequest $request, Exam $exam, Question $question)
    {
        $user = $request->user();
        abort_unless($user, 401);

        if ($question->exam_id !== $exam->id) {
            abort(404);
        }

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $question->fill($request->validated());
        $question->save();
        return response()->json(['message' => 'Question updated']);
    }

    public function destroyQuestion(Request $request, Exam $exam, Question $question)
    {
        $user = $request->user();
        abort_unless($user, 401);
        if ($question->exam_id !== $exam->id) {
            abort(404);
        }

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $question->delete();
        $exam->decrement('questions_count');
        return response()->json(['message' => 'Question deleted']);
    }

    // Options CRUD
    public function storeOption(StoreQuestionOptionRequest $request, Exam $exam, Question $question)
    {
        $user = $request->user();
        abort_unless($user, 401);
        if ($question->exam_id !== $exam->id) {
            abort(404);
        }

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $option = $question->options()->create($request->validated());
        return response()->json(['message' => 'Option created', 'id' => $option->id], 201);
    }

    public function updateOption(UpdateQuestionOptionRequest $request, Exam $exam, Question $question, QuestionOption $option)
    {
        $user = $request->user();
        abort_unless($user, 401);
        if ($question->exam_id !== $exam->id || $option->question_id !== $question->id) {
            abort(404);
        }

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $option->fill($request->validated());
        $option->save();
        return response()->json(['message' => 'Option updated']);
    }

    public function destroyOption(Request $request, Exam $exam, Question $question, QuestionOption $option)
    {
        $user = $request->user();
        abort_unless($user, 401);
        if ($question->exam_id !== $exam->id || $option->question_id !== $question->id) {
            abort(404);
        }

        $isTeacher = $user->roles()->where('name', 'teacher')->exists();
        if ($isTeacher && $exam->course->instructor_id !== $user->id) {
            abort(403, 'Forbidden');
        }

        $option->delete();
        return response()->json(['message' => 'Option deleted']);
    }
}
