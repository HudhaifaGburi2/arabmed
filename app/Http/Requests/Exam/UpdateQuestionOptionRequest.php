<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'option_ar' => 'sometimes|required|string',
            'option_en' => 'nullable|string',
            'is_correct' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ];
    }
}
