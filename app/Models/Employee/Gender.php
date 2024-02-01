<?php

namespace App\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function employee()
    {
        return $this->hasMany(Employee::class, 'gender_id');
    }

    public function PolicyLeaveType()
    {
        return $this->hasMany(PolicyLeaveType::class, 'gender_id');
    }

    public function LeaveType()
    {
        return $this->hasMany(LeaveType::class, 'gender_id');
    }
}
