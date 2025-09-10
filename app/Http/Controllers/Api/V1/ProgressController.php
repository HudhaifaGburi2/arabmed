<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgressResource;
use App\Models\{User, Video, AudioRecording, VideoProgress, AudioProgress};
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function videoProgress(Request $request, User $user)
    {
        abort_unless($request->user() && $request->user()->id === $user->id, 403);
        $items = $user->videoProgress()->with('video')
            ->orderByDesc('updated_at')
            ->paginate($request->integer('per_page', 20));
        return ProgressResource::collection($items);
    }

    public function audioProgress(Request $request, User $user)
    {
        abort_unless($request->user() && $request->user()->id === $user->id, 403);
        $items = AudioProgress::with('audio')
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->paginate($request->integer('per_page', 20));
        return ProgressResource::collection($items);
    }

    public function updateVideoProgress(Request $request, Video $video)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $data = $request->validate([
            'watched_seconds' => 'required|integer|min:0',
            'total_seconds' => 'required|integer|min:1',
            'last_position_seconds' => 'nullable|integer|min:0',
        ]);

        $progress = VideoProgress::firstOrNew([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        $progress->total_seconds = max($progress->total_seconds, $data['total_seconds']);
        $progress->watched_seconds = max($progress->watched_seconds, $data['watched_seconds']);
        $progress->last_position_seconds = $data['last_position_seconds'] ?? $progress->last_position_seconds ?? 0;
        $progress->progress_percentage = min(100, round(($progress->watched_seconds / max(1, $progress->total_seconds)) * 100, 2));
        if ($progress->watched_seconds >= $progress->total_seconds && !$progress->completed_at) {
            $progress->completed_at = now();
        }
        $progress->updated_at = now();
        $progress->save();

        return new ProgressResource($progress->load('video'));
    }

    public function updateAudioProgress(Request $request, AudioRecording $audio)
    {
        $user = $request->user();
        abort_unless($user, 401);

        $data = $request->validate([
            'listened_seconds' => 'required|integer|min:0',
            'total_seconds' => 'required|integer|min:1',
            'last_position_seconds' => 'nullable|integer|min:0',
        ]);

        $progress = AudioProgress::firstOrNew([
            'user_id' => $user->id,
            'audio_id' => $audio->id,
        ]);

        $progress->total_seconds = max($progress->total_seconds, $data['total_seconds']);
        $progress->listened_seconds = max($progress->listened_seconds, $data['listened_seconds']);
        $progress->last_position_seconds = $data['last_position_seconds'] ?? $progress->last_position_seconds ?? 0;
        $progress->progress_percentage = min(100, round(($progress->listened_seconds / max(1, $progress->total_seconds)) * 100, 2));
        if ($progress->listened_seconds >= $progress->total_seconds && !$progress->completed_at) {
            $progress->completed_at = now();
        }
        $progress->updated_at = now();
        $progress->save();

        return new ProgressResource($progress->load('audio'));
    }
}
