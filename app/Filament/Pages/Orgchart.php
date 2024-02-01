<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;


class Orgchart extends Page
{
    public $employees;
    public $expanded = []; // Add this


    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $slug = 'orgchart';
    protected static string $view = 'filament.pages.orgchart';

    public function mount()
    {
        $this->employees = User::whereHas('jobInfo', function($query){
            $query->whereNull('report_to');
        })->with('jobInfo', 'jobInfo.designation', 'employee')->get();

        // Automatically expand the first level:
        foreach ($this->employees as $employee) {
            $this->expanded[] = $employee->id;
        }
    }

    // Add a method to toggle the expanded status of an employee:
    
    public function toggleExpand($id)
    {

        if (in_array($id, $this->expanded)) {
            $this->expanded = array_diff($this->expanded, [$id]);
        } else {
            $this->expanded[] = $id;

        }
    }

}
