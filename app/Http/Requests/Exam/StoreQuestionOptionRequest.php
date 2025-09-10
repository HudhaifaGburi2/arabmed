<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'option_ar' => 'required|string',
            'option_en' => 'nullable|string',
            'is_correct' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ];
    }
}
