<?php

namespace App\Livewire;

use App\Events\MessageSend;
use App\Models\Timesheet\ProjectChat as TimesheetProjectChat;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectChat extends Component
{
    public $message;
    public $record;
    public $viewChats;
    public function mount($record)
    {
        $this->record = $record;
        $this->viewChats = TimesheetProjectChat::with('users.jobInfo.designation')->where('project_id',$this->record->id)->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->toArray();

    }
    #[On('getData')]
    public function editmessage(){
        $this->viewChats = TimesheetProjectChat::with('users.jobInfo.designation')->where('project_id',$this->record->id)->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->toArray();
    }
    #[On('sendmessage')]
    public function Message($message)
    {
       if($message){
           // $ProjectChat = TimesheetProjectChat::where('project_id',$this->record->id)->get();
           TimesheetProjectChat::create([
               'project_id' => $this->record->id,
               'user_id' => auth()->user()->id,
               'message' => $message,
           ]);
           event(new MessageSend($message));
        
       }
    }
    public function render()
    {
        return view('livewire.project-chat');
    }
}
