<?php

namespace App\Models\TimeOff;

use App\Models\TimeOff\LeaveApproval;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'policy_leave_type_id',
        'days_taken',
        'reason',
        'status',
       
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(PolicyLeaveType::class, 'policy_leave_type_id');
    }

    public function leaveDates()
    {
        return $this->hasMany(LeaveDate::class, 'leave_id');
    }

    // Add this method
    public function leaveApprovals()
    {
        return $this->hasMany(LeaveApproval::class, 'leave_id');
    }
}
