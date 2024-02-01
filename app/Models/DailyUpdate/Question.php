<?php

namespace App\Models\DailyUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'frequency',
        'start_time',
        'end_time',
    ];
    
    public function taskUser()
    {
        return $this->hasMany(TaskUser::class,'question_id');
    }

    public function day()
    {
        return $this->hasMany(Day::class,'question_id');
    }
}
