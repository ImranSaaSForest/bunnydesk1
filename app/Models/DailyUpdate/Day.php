<?php

namespace App\Models\DailyUpdate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'day',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class,'question_id');
    }

}
