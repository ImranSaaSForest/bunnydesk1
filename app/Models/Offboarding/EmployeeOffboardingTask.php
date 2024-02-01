<?php

namespace App\Models\Offboarding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOffboardingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_offboarding_id',
        'offboarding_task_id',
        'status',
        'comment',
    ];

    public function employeeOffboardingTask()
    {
        return $this->belongsTo(EmployeeOffboarding::class, 'employee_offboarding_id');
    }

    public function offboardingTask()
    {
        return $this->belongsTo(OffboardingTask::class, 'offboarding_task_id');
    }

}
