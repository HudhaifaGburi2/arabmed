<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;
        return [
            'title_ar' => 'sometimes|required|string|max:300',
            'title_en' => 'nullable|string|max:300',
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('courses', 'slug')->ignore($courseId)
            ],
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string|max:500',
            'short_description_en' => 'nullable|string|max:500',
            'thumbnail_url' => 'nullable|url|max:500',
            'cover_image_url' => 'nullable|url|max:500',
            'trailer_video_url' => 'nullable|url|max:500',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'duration_minutes' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer',
        ];
    }
}
