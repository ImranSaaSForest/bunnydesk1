<?php

namespace App\Models\Offboarding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffboardingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    public function offboardingTask()
    {
        return $this->hasMany(OffboardingTask::class, 'offboarding_list_id');
    }
    public function tasks()
    {
        return $this->hasMany(OffboardingTask::class, 'offboarding_list_id');
    }

}
