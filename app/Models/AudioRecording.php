<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\HasSlug;

class AudioRecording extends Model
{
    use HasFactory, HasUuid, HasSlug;

    protected $fillable = [
        'uuid','course_id','video_id','title_ar','title_en','slug','description_ar','description_en','audio_url','duration_seconds','file_size_mb','format','bitrate_kbps','thumbnail_url','sort_order','is_free','is_published','plays_count','downloads_count'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_published' => 'boolean',
        'file_size_mb' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
