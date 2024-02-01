<?php

namespace App\Models\Timesheet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function timesheetApprovals()
    {
        return $this->hasMany(TimesheetApproval::class);
    }

  /**
     * Get the user who created the payroll policy.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the payroll policy.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    
    public function getStatusAttribute($value)
    {
        // Only override for Supervisors
        if(auth()->user()->hasRole('Supervisor')) {
            $latestApproval = $this->timesheetApprovals()->latest()->first();
            return $latestApproval ? $latestApproval->status : $value;
        }
        return $value;
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($timesheet) {
            if (Auth::check()) {
                $timesheet->created_by = Auth::id();
            }
        });

        static::updating(function ($timesheet) {
            if (Auth::check()) {
                $timesheet->updated_by = Auth::id();
            }
        });
    }

    
}
