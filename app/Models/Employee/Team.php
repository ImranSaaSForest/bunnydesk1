<?php

namespace App\Models\Employee;

use App\Models\ProjectTeam;
use App\Models\TimeOff\Policy;
use App\Models\Timesheet\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function jobInfo()
    {
        return $this->hasMany(JobInfo::class, 'team_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'team_id');
    }

    public function policies()
    {
        return $this->belongsToMany(Policy::class, 'team_policy');
    }
    public function budgetTeam()
    {
        return $this->hasMany(BudgetTeam::class);
    }

    public function teamProjects()
    {
        return $this->hasMany(ProjectTeam::class, 'team_id');
    }

}
