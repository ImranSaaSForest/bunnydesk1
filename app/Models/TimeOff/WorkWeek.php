<?php

namespace App\Models\TimeOff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkWeek extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'week_start_day',
        'is_active',
    ];

    public function workWeekDays()
    {
        return $this->hasMany(WorkWeekDay::class, 'work_week_id');
    }

    public function Policy()
    {
        return $this->hasMany(Policy::class, 'work_week_id');
    }
}
