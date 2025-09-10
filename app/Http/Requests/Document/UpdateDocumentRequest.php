<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => 'nullable|integer|exists:courses,id',
            'video_id' => 'nullable|integer|exists:videos,id',
            'title_ar' => 'sometimes|required|string|max:300',
            'title_en' => 'nullable|string|max:300',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'file_url' => 'nullable|url|max:500',
            'file_type' => 'nullable|in:pdf,doc,docx,ppt,pptx,txt,image',
            'file_size_mb' => 'nullable|numeric|min:0',
            'thumbnail_url' => 'nullable|url|max:500',
            'is_downloadable' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ];
    }
}
