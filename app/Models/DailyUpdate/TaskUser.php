<?php

namespace App\Models\DailyUpdate;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'question_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class,'question_id');
    }
    
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
