<?php

namespace App\Livewire;

use App\Models\upload;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileUpload extends Component

{
 use WithFileUploads;
    public $files;

    public function fileUpload(){
       
        // $filename=$this->files->store('files','public');

        // $validateData['files']=$filename;
        
        session()->flash('message','File Successfully Uploaded...!');
        
    }
    

    public function render()
    {
        return view('livewire.file-upload');
    }
}
