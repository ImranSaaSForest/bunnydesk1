<?php

namespace App\Livewire;

use Livewire\Component;

class ProjectNavigation extends Component
{
    public $record;

    public function mount($record)
    {
        $this->tab = request('tab');
        // dd($this->tab);
        $this->record = $record;
        // $this->project = Project::where('id', $record->id)->with('tasks')->get();
    }


    public function render()
    {
        return view('livewire.project-navigation');
    }
}
