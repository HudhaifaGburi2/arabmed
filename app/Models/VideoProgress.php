<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id','video_id','watched_seconds','total_seconds','progress_percentage','completed_at','last_position_seconds','updated_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'updated_at' => 'datetime',
        'progress_percentage' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
