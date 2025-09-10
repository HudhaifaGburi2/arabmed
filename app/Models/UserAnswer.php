<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'attempt_id','question_id','selected_option_id','text_answer','is_correct','marks_obtained','answered_at'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'is_correct' => 'boolean',
        'marks_obtained' => 'decimal:2',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }
}
