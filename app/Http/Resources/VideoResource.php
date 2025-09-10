<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'slug' => $this->slug,
            'video_url' => $this->video_url,
            'thumbnail_url' => $this->thumbnail_url,
            'duration_seconds' => $this->duration_seconds,
            'video_quality' => $this->video_quality,
            'is_free' => (bool) $this->is_free,
            'is_published' => (bool) $this->is_published,
            'views_count' => $this->views_count,
            'likes_count' => $this->likes_count,
        ];
    }
}
