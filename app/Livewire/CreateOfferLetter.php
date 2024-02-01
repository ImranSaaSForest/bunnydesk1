<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class CreateOfferLetter extends Component
{


    use WithFileUploads;
    public $files;
    
    public $emp_id;

    public function mount($id){
        $this->emp_id=$id;
    }
    public function fileUpload(){
       
        // $filename=$this->files->store('files','public');

        // $validateData['files']=$filename;
        
        session()->flash('message','File Successfully Uploaded...!');
        
    }
    

    public function render()
    {
        return view('livewire.create-offer-letter');
    }
}
