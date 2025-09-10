<?php

namespace App\Http\Requests\Video;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $video = $this->route('video');
        $courseId = $video?->course_id ?? $this->input('course_id');
        return [
            'course_id' => 'sometimes|required|integer|exists:courses,id',
            'title_ar' => 'sometimes|required|string|max:300',
            'title_en' => 'nullable|string|max:300',
            'slug' => [
                'nullable','string','max:255',
                Rule::unique('videos', 'slug')
                    ->where(fn($q)=>$q->where('course_id', $courseId))
                    ->ignore($video?->id)
            ],
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'thumbnail_url' => 'nullable|url|max:500',
            'duration_seconds' => 'nullable|integer|min:0',
            'file_size_mb' => 'nullable|numeric|min:0',
            'video_quality' => 'nullable|in:360p,720p,1080p,4k',
            'sort_order' => 'nullable|integer',
            'is_free' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ];
    }
}
