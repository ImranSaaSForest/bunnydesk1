<?php

namespace App\Models\Attendance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AttendanceRecord extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'attendance_type_id',
        'status',
        'reason',
        'in',
        'out',
        'total_hours'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceType()
    {
        return $this->belongsTo(AttendanceType::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the social security.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attendanceRecord) {
            if (Auth::check()) {
                $attendanceRecord->created_by = Auth::id();
            }
        });

        static::updating(function ($attendanceRecord) {
            if (Auth::check()) {
                $attendanceRecord->updated_by = Auth::id();
            }
        });
    }

}
