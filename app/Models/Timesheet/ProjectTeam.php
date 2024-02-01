<?php

namespace App\Models\Timesheet;

use App\Models\Employee\Team;
use App\Models\Timesheet\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'team_id',
    ];

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function teams()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
