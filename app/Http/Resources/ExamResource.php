<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'course_id' => $this->course_id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'instructions_ar' => $this->instructions_ar,
            'instructions_en' => $this->instructions_en,
            'time_limit_minutes' => $this->time_limit_minutes,
            'max_attempts' => $this->max_attempts,
            'passing_score' => (float) $this->passing_score,
            'total_marks' => (float) $this->total_marks,
            'questions_count' => $this->questions_count,
            'is_active' => (bool) $this->is_active,
            'starts_at' => optional($this->starts_at)?->toISOString(),
            'ends_at' => optional($this->ends_at)?->toISOString(),
            'questions' => $this->whenLoaded('questions', function () {
                return $this->questions->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'uuid' => $q->uuid,
                        'question_ar' => $q->question_ar,
                        'question_en' => $q->question_en,
                        'question_type' => $q->question_type,
                        'marks' => (float) $q->marks,
                        'options' => $q->whenLoaded('options', fn() => $q->options->map(fn($o) => [
                            'id' => $o->id,
                            'option_ar' => $o->option_ar,
                            'option_en' => $o->option_en,
                        ])),
                    ];
                });
            }),
        ];
    }
}
