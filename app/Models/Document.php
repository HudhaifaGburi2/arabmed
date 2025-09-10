<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Document extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid','course_id','video_id','title_ar','title_en','description_ar','description_en','file_url','file_type','file_size_mb','thumbnail_url','downloads_count','is_downloadable','sort_order'
    ];

    protected $casts = [
        'is_downloadable' => 'boolean',
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
