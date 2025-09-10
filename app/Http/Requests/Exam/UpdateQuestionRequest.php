<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_ar' => 'sometimes|required|string',
            'question_en' => 'nullable|string',
            'question_type' => 'nullable|in:multiple_choice,true_false,short_answer,essay',
            'marks' => 'nullable|numeric|min:0',
            'explanation_ar' => 'nullable|string',
            'explanation_en' => 'nullable|string',
            'image_url' => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
    }
}
