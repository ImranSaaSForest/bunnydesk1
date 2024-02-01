<?php

namespace App\Models\Company;

use App\Models\Employee\JobInfo;
use App\Models\Recruitment\Job;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id', 
        'name'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function jobInfo()
    {
        return $this->hasMany(JobInfo::class, 'designation_id');
    }

    public function jobDesignation()
    {
        return $this->hasMany(Job::class, 'designation_id');
    }
}
