<?php

namespace App\Filament\Resources\Timesheet\ProjectResource\Pages;

use App\Filament\Resources\Timesheet\ProjectResource;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\On;

class SortProjects extends Page
{
    protected static string $resource = ProjectResource::class;

    protected static ?string $title = 'Projects';

    // protected static string $view = 'filament.resources.timesheet.project-resource.pages.sort-projects';

    // public $tab = '';

    // #[On('tab-select')]
    // public function updatePostList($val)
    // {
    //     dd($val);
    // }

}
