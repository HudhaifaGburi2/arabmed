<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Exam, ExamAttempt, Question, QuestionOption, UserAnswer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamAttemptController extends Controller
{
    public function start(Request $request, Exam $exam)
    {
        $user = $request->user();
        abort_unless($user, 401);

        // Check schedule
        if (!$exam->is_active) {
            return response()->json(['message' => 'Exam is not active'], 422);
        }
        if ($exam->starts_at && now()->lt($exam->starts_at)) {
            return response()->json(['message' => 'Exam has not started yet'], 422);
        }
        if ($exam->ends_at && now()->gt($exam->ends_at)) {
            return response()->json(['message' => 'Exam has ended'], 422);
        }

        // Check attempts limit
        $attemptsCount = ExamAttempt::where('user_id', $user->id)->where('exam_id', $exam->id)->count();
        if ($exam->max_attempts && $attemptsCount >= $exam->max_attempts) {
            return response()->json(['message' => 'Max attempts reached'], 422);
        }

        $attempt = ExamAttempt::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'started_at' => now(),
            'attempt_number' => $attemptsCount + 1,
        ]);

        return response()->json([
            'attempt_id' => $attempt->id,
            'uuid' => $attempt->uuid,
            'started_at' => $attempt->started_at?->toISOString(),
            'time_limit_minutes' => $exam->time_limit_minutes,
            'max_attempts' => $exam->max_attempts,
        ], 201);
    }

    public function submit(Request $request, Exam $exam)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $data = $request->validate([
            'attempt_id' => 'required|integer|exists:exam_attempts,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.selected_option_id' => 'nullable|integer|exists:question_options,id',
            'answers.*.text_answer' => 'nullable|string',
        ]);

        $attempt = ExamAttempt::where('id', $data['attempt_id'])
            ->where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->firstOrFail();

        if ($attempt->submitted_at) {
            return response()->json(['message' => 'Attempt already submitted'], 422);
        }

        // Optional: enforce time limit
        if ($exam->time_limit_minutes) {
            $deadline = $attempt->started_at?->clone()->addMinutes($exam->time_limit_minutes);
            if ($deadline && now()->gt($deadline)) {
                // continue but flag overtime if needed
            }
        }

        $questions = Question::where('exam_id', $exam->id)->with('options')->get()->keyBy('id');

        $obtained = 0.0;
        $total = (float) ($exam->total_marks ?: $questions->sum(fn($q) => (float) $q->marks));
        $storedAnswers = [];

        DB::transaction(function () use ($data, $questions, $attempt, &$obtained, &$storedAnswers) {
            foreach ($data['answers'] as $ans) {
                $qid = (int) $ans['question_id'];
                if (!isset($questions[$qid])) {
                    continue;
                }
                $question = $questions[$qid];

                $selectedOptionId = $ans['selected_option_id'] ?? null;
                $textAnswer = $ans['text_answer'] ?? null;
                $isCorrect = null;
                $marksObtained = 0.0;

                if (in_array($question->question_type, ['multiple_choice','true_false'])) {
                    if ($selectedOptionId) {
                        /** @var QuestionOption|null $opt */
                        $opt = $question->options->firstWhere('id', (int) $selectedOptionId);
                        if ($opt) {
                            $isCorrect = (bool) $opt->is_correct;
                            if ($isCorrect) {
                                $marksObtained = (float) $question->marks;
                            }
                        }
                    }
                } elseif ($question->question_type === 'short_answer') {
                    // Leave for manual grading; optionally naive check could be implemented
                    $isCorrect = null;
                    $marksObtained = 0.0;
                } else { // essay
                    $isCorrect = null;
                    $marksObtained = 0.0;
                }

                $obtained += $marksObtained;

                $ua = UserAnswer::updateOrCreate(
                    [
                        'attempt_id' => $attempt->id,
                        'question_id' => $qid,
                    ],
                    [
                        'selected_option_id' => $selectedOptionId,
                        'text_answer' => $textAnswer,
                        'is_correct' => $isCorrect,
                        'marks_obtained' => $marksObtained,
                        'answered_at' => now(),
                    ]
                );
                $storedAnswers[] = $ua->id;
            }

            $percentage = $total > 0 ? round(($obtained / $total) * 100, 2) : 0.0;
            $attempt->update([
                'submitted_at' => now(),
                'time_taken_seconds' => $attempt->started_at ? now()->diffInSeconds($attempt->started_at) : null,
                'total_marks' => $total,
                'obtained_marks' => $obtained,
                'percentage' => $percentage,
                'is_passed' => $percentage >= (float) ($attempt->exam->passing_score ?? 0),
                'answers' => $storedAnswers,
            ]);
        });

        return response()->json([
            'message' => 'Attempt submitted',
            'attempt' => [
                'id' => $attempt->id,
                'uuid' => $attempt->uuid,
                'submitted_at' => $attempt->submitted_at?->toISOString(),
                'percentage' => (float) $attempt->percentage,
                'obtained_marks' => (float) $attempt->obtained_marks,
                'total_marks' => (float) $attempt->total_marks,
                'is_passed' => (bool) $attempt->is_passed,
            ],
        ]);
    }
}
