<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'course_id' => $this->course_id,
            'video_id' => $this->video_id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'file_url' => $this->file_url,
            'file_type' => $this->file_type,
            'file_size_mb' => (float) $this->file_size_mb,
            'thumbnail_url' => $this->thumbnail_url,
            'downloads_count' => $this->downloads_count,
            'is_downloadable' => (bool) $this->is_downloadable,
        ];
    }
}
