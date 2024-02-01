<?php

namespace App\Livewire;

use App\Models\Employee\EmployeeType;
use App\Models\Recruitment\Job;
use Livewire\Component;

class JobDescription extends Component
{
    public $Job;
    public $emptyp;

    public function mount($id) {
        $getJob = Job::where('id', $id)->get();
        $this->Job = $getJob[0];
        $getemptyp = $getJob[0]->employee_type_id;
        $getEmpName = EmployeeType::where('id', $getemptyp)->pluck('name');
        $this->emptyp = $getEmpName[0];
        //  dd( $this->emptyp);
        // dd($this->emptyp[0]->employeeType->name);
    }
    public function render()
    {
        return view('livewire.job-description');
    }
}
