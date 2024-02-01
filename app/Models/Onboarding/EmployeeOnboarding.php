<?php

namespace App\Models\Onboarding;

use App\Models\User;
use App\Models\Onboarding\OnboardingList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_by',
        'onboarding_list_id',
        'comment',
    ];

    public function onboardingList()
    {
        return $this->belongsTo(OnboardingList::class, 'onboarding_list_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function emponboarding()
    {
        return $this->hasMany(EmployeeOnboardingTask::class,'employee_onboarding_id');
    }
    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }


}
