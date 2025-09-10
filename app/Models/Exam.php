<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Exam extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid','course_id','title_ar','title_en','description_ar','description_en','instructions_ar','instructions_en','time_limit_minutes','max_attempts','passing_score','total_marks','questions_count','is_active','starts_at','ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'passing_score' => 'decimal:2',
        'total_marks' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
