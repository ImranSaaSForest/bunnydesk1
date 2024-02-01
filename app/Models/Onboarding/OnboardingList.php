<?php

namespace App\Models\Onboarding;

use App\Models\Company\Company;
use App\Models\Recruitment\Job;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    public function tasks()
    {
        return $this->hasMany(OnboardingTask::class, 'onboarding_list_id');
    }
    // public function job()
    // {
    //     return $this->hasMany(Job::class, 'onboarding_list_id');
    // }
}
