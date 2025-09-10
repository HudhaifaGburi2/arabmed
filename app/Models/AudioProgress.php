<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioProgress extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id','audio_id','listened_seconds','total_seconds','progress_percentage','completed_at','last_position_seconds','updated_at'
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

    public function audio()
    {
        return $this->belongsTo(AudioRecording::class, 'audio_id');
    }
}
