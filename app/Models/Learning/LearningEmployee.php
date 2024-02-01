<?php

namespace App\Models\Learning;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
        'progress',
        'quiz',
        'feedback'

    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
