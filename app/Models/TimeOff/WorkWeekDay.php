<?php

namespace App\Models\TimeOff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkWeekDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'work_week_id',
        'is_active',
    ];

    public function workWeek()
    {
        return $this->belongsTo(WorkWeek::class, 'work_week_id');
    }
}
