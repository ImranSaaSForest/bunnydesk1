<?php

namespace App\Models\Onboarding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboardingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_onboarding_id',
        'onboarding_task_id',
        'status',
        'comment',
    ];

    public function employeeOnboardingTask()
    {
        return $this->belongsTo(EmployeeOnboarding::class, 'employee_onboarding_id');
    }

    public function onboardingTask()
    {
        return $this->belongsTo(OnboardingTask::class, 'onboarding_task_id');
    }

}
