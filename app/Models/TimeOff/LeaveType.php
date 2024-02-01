<?php

namespace App\Models\TimeOff;

use App\Models\Employee\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'days_allowed',
        'frequency',
        'auto_approve',
        'color',
        'gender_id',
    ];

    public function policyLeaveType()
    {
        return $this->hasMany(PolicyLeaveType::class, 'leave_type_id');
    }


    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

}
