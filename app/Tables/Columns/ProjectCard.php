<?php

namespace App\Tables\Columns;

use App\Models\Timesheet\Task;
use Filament\Tables\Columns\Column;

class ProjectCard extends Column
{
    protected string $view = 'tables.columns.project-card';

    public $project;
    public function mount(){
        dd($this->record);
    // $totalTasks = Task::where('project_id', $projectId)->count();
    }
}
