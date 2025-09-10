<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'slug' => $this->slug,
            'thumbnail_url' => $this->thumbnail_url,
            'cover_image_url' => $this->cover_image_url,
            'instructor' => $this->whenLoaded('instructor', function () {
                return [
                    'id' => $this->instructor->id,
                    'first_name' => $this->instructor->first_name,
                    'last_name' => $this->instructor->last_name,
                ];
            }),
            'category' => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'name_ar' => $this->category->name_ar,
                'name_en' => $this->category->name_en,
                'slug' => $this->category->slug,
            ]),
            'level' => $this->level,
            'duration_minutes' => $this->duration_minutes,
            'price' => (float) $this->price,
            'is_free' => (bool) $this->is_free,
            'is_published' => (bool) $this->is_published,
            'published_at' => optional($this->published_at)?->toISOString(),
            'stats' => [
                'views' => $this->views_count,
                'enrollments' => $this->enrollments_count,
                'rating_avg' => (float) $this->rating_avg,
                'ratings_count' => $this->ratings_count,
            ],
        ];
    }
}
