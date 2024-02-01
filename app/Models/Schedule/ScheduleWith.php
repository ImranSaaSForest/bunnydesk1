<?php

namespace App\Models\Schedule;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleWith extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_id',
        'user_id'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
