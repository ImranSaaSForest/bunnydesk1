<?php

namespace App\Models\Schedule;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_date',
        'event_time',
        'notify_at',
        'notification_time',
        'about',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($Schedule) {
            if (Auth::check()) {
                $Schedule->created_by = Auth::id();
            }
        });

        static::updating(function ($Schedule) {
            if (Auth::check()) {
                $Schedule->updated_by = Auth::id();
            }
        });
    }

    public function scheduleWith()
    {
        return $this->hasMany(ScheduleWith::class, 'schedule_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
