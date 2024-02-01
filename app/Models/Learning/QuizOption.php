<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    use HasFactory;
    protected $fillable=[
'quiz_id',
'option',
'is_correct'

    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
    public function quizResponse()
    {
        return $this->hasMany(QuizResponse::class, 'quiz_option_id');
    }
}
