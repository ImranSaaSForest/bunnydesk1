<?php

namespace App\Models\Onboarding;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'onboarding_list_id',
        'name',
        'user_id',
        'duration',
        'description',
    ];

    public function onboardingList()
    {
        return $this->belongsTo(OnboardingList::class, 'onboarding_list_id');
    }

    public function empOnTask()
    {
        return $this->hasMany(EmployeeOnboardingTask::class, 'onboarding_task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
