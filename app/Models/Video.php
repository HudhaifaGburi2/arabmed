<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\HasSlug;

class Video extends Model
{
    use HasFactory, HasUuid, HasSlug;

    protected $fillable = [
        'uuid','course_id','title_ar','title_en','slug','description_ar','description_en','video_url','thumbnail_url','duration_seconds','file_size_mb','video_quality','sort_order','is_free','is_published','views_count','likes_count'
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

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function audioRecordings()
    {
        return $this->hasMany(AudioRecording::class, 'video_id');
    }

    public function progress()
    {
        return $this->hasMany(VideoProgress::class);
    }
}
