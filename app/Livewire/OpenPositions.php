<?php

namespace App\Livewire;

use App\Models\Company\Company;
use App\Models\Recruitment\Job;
use Livewire\Component;

class OpenPositions extends Component
{
    public $branch;

    public function company(){

        if($this->branch == "all") {
            $this->Jobs = Job::where('job_status', 'approved')->get();
        }
        else{
        $this->Jobs = Job::where('company_id', $this->branch)->where('job_status', 'approved')->with('designation')->get();
        }
    }

    public $companies;
    public $Jobs = [];

    public function mount() {

        $this->companies = Company::all();
        $this->Jobs = Job::with('designation')->where('job_status', 'approved')->get();
        // if(count($this->companies) > 0){
        //     dd(count($this->companies));
        // }
        // else {
        //     dd('no');
        // }
        // dd($this->companies);
    }

    public function render()
    {

        return view('livewire.open-positions');
    }
}
