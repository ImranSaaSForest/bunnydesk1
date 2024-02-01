<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable=[
        'course_id',
        'question',
    ];
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function options()
    {
        return $this->hasMany(QuizOption::class,);
    }
    public function quizResponse()
    {
        return $this->hasMany(QuizResponse::class);
    }
}
