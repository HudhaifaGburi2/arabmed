<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'watched_seconds' => $this->watched_seconds ?? $this->listened_seconds ?? 0,
            'total_seconds' => $this->total_seconds,
            'progress_percentage' => (float) $this->progress_percentage,
            'completed_at' => optional($this->completed_at)?->toISOString(),
            'last_position_seconds' => $this->last_position_seconds,
            'updated_at' => optional($this->updated_at)?->toISOString(),
            'video' => $this->whenLoaded('video', fn() => [
                'id' => $this->video->id,
                'title_ar' => $this->video->title_ar,
                'title_en' => $this->video->title_en,
            ]),
            'audio' => $this->whenLoaded('audio', fn() => [
                'id' => $this->audio->id,
                'title_ar' => $this->audio->title_ar,
                'title_en' => $this->audio->title_en,
            ]),
        ];
    }
}
