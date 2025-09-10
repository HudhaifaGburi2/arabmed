<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Question extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid','exam_id','question_ar','question_en','question_type','marks','explanation_ar','explanation_en','image_url','sort_order','is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'marks' => 'decimal:2',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
