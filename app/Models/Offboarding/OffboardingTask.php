<?php

namespace App\Models\Offboarding;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffboardingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'offboarding_list_id',
        'name',
        'user_id',
        'duration',
        'description',
    ];

    public function offboardingList()
    {
        return $this->belongsTo(OffboardingList::class, 'offboarding_list_id');
    }
    //bala added
    public function empoffboardingTask()
    {
        return $this->hasMany(EmployeeOffboardingTask::class, 'offboarding_task_id');
    }
    //bala added

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
