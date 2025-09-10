<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use App\Traits\HasSlug;

class Course extends Model
{
    use HasFactory, HasUuid, HasSlug;

    protected $fillable = [
        'uuid','title_ar','title_en','slug','description_ar','description_en','short_description_ar','short_description_en','thumbnail_url','cover_image_url','trailer_video_url','instructor_id','category_id','level','duration_minutes','price','is_free','is_published','published_at','sort_order','views_count','enrollments_count','rating_avg','ratings_count'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
        'rating_avg' => 'decimal:2',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function audioRecordings()
    {
        return $this->hasMany(AudioRecording::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }
}
