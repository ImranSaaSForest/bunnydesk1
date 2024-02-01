<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResponse extends Model
{
    use HasFactory;
    protected $fillable=[
        'quiz_id',
        'quiz_option_id',
        'user_id'
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
    public function quizOption()
    {
        return $this->belongsTo(QuizOption::class, 'quiz_option_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
