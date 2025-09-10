<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'sometimes|required|integer|exists:courses,id',
            'title_ar' => 'sometimes|required|string|max:300',
            'title_en' => 'nullable|string|max:300',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'instructions_ar' => 'nullable|string',
            'instructions_en' => 'nullable|string',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|numeric|min:0|max:100',
            'total_marks' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }
}
