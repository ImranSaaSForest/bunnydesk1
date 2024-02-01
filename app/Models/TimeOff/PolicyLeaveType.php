<?php

namespace App\Models\TimeOff;

use App\Models\Employee\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyLeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'days_allowed',
        'frequency',
        'policy_id',
        'leave_type_id',
        'last_reset_date',
        'is_paid',
        'negative_leave_balance'

    ];



    public function policy()
    {
        return $this->belongsTo(Policy::class, 'policy_id');
    }

    public function leave()
    {
        return $this->hasMany(Leave::class, 'policy_leave_type_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
