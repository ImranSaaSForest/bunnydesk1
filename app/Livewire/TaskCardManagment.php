<?php

namespace App\Livewire;

use App\Filament\Resources\Timesheet\TimesheetResource as TimesheetTimesheetResource;
use App\Filament\Resources\TimesheetResource;
use App\Models\Timesheet\Project;
use App\Models\Timesheet\Task;
use App\Models\Timesheet\Timesheet;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Actions\Action ;
use Livewire\Attributes\On;

use Livewire\Component;

class TaskCardManagment extends Component
{
    public $clicktap='1';
    public $record;
    public $project_assined;
    public $planing;
    public $project_inpro;
    public $project_done;
    public $project_ready;

    public function mount()
    {
        // static::authorizeResourceAccess();
        $this->callStatus($this->record->id);
        // dd($this->record->id);
        // 'assigned', 'inprogress', 'submitted','done','planning','ready'
    }

 public $card_id;
 public $store_id;
    #[On('post-created')]
    public function save($postId,$id){
        // dd($id,$postId);
        $this->store_id=$id;
        $this->card_id=$postId;
        if($this->store_id=='list2'){
            Task::where('id', $this->card_id)->update(['status' => 'inprogress']);
        }
        elseif($this->store_id=='list3'){
            Task::where('id', $this->card_id)->update(['status' => 'ready']);
            $notify_val=1;
            $this->Notify($this->card_id,$notify_val);
        }
        elseif($this->store_id=='list4'){
            Task::where('id',$this->card_id)->update(['status' => 'done']);
            $notify_val=2;
            $this->Notify($this->card_id,$notify_val);
        }
        elseif($this->store_id=='list1'){
            // dd($this->card_id);
            Task::where('id', $this->card_id)->update(['status' => 'planing']);
        }elseif($this->store_id=='list0'){
            Task::where('id', $this->card_id)->update(['status' => 'assigned']);
        }else{
        }

        $this->callStatus($this->record->id);
    }
    public $count_assined;
    public $count_planing;
    public $count_inpro;
    public $count_ready;
    public $count_done;
    //count the task record respective statuses
    public function callStatus($id){

        $loggedInUserId=auth()->id();
        $this->project_assined=Task::where('project_id',$id)->where('status','assigned')->with('users.employee','createdBy')->orderByRaw("user_id = $loggedInUserId DESC, created_at DESC")->get();
        // dd( $this->project_assined);
        $this->planing=Task::where('project_id',$id)->where('status','planing')->with('users.employee','createdBy')->orderByRaw("user_id = $loggedInUserId DESC, created_at DESC")->get();
        $this->project_inpro=Task::where('project_id',$id)->where('status','inprogress')->with('users.employee','createdBy')->orderByRaw("user_id = $loggedInUserId DESC, created_at DESC")->get();
        $this->project_done=Task::where('project_id',$id)->where('status','done')->with('users.employee','createdBy')->orderByRaw("user_id = $loggedInUserId DESC, created_at DESC")->get();
        $this->project_ready=Task::where('project_id',$id)->where('status','ready')->with('users.employee','createdBy')->orderByRaw("user_id = $loggedInUserId DESC, created_at DESC")->get();
        // dd($id);
        $this->StatusCount($id);
    }
    public function StatusCount($id){
        $this->count_assined=Task::where('project_id',$id)->where('status','assigned')->count();
        $this->count_planing=Task::where('project_id',$id)->where('status','planing')->count();
        $this->count_inpro=Task::where('project_id',$id)->where('status','inprogress')->count();
        $this->count_ready=Task::where('project_id',$id)->where('status','ready')->count();
        $this->count_done=Task::where('project_id',$id)->where('status','done')->count();
    }
    //task clicked then open modal details
    public $task_assiner;
    public $task_name;
    public $task_desc;
    public $project_name;
    public function open($task_id){
        $task_details=Task::find($task_id);
        $task_ass=Task::where('task_id',$task_id)->get();
        $user=User::find($task_ass[0]->created_by);
        $project_n=Project::find($task_details->project_id);
        $this->project_name= $project_n->name;
        $this->task_assiner=$user->name;
        $this->task_name= $task_details->name;
        $this->task_desc=$task_details->description;
        $this->dispatch('open-modal', id: 'open');//open card details model
    }
    // open for respective taps chat,task,docs
    public function OpenTap($value){
        $this->clicktap=$value;
    }
    // notification function
    public function Notify($id,$notify_val){
    $notifi=Task::where('id', $id)->get();
            $Task=Task::where('id', $id)->first();
            $userID=User::find($Task->created_by);
            $recipient = $userID;
            //get task name
            // $task_name=Task::find($project_id[0]->task_id);
            // dd($task_name);
            if($notify_val==1){
                $msg='The Task '.$Task->name.' is at Ready for QA';
            }else{
                $msg='The Task '.$Task->name.' is Completed';
            }
            $recipient->notify(
                Notification::make()
                    ->title($msg)
                    ->actions([
                        Action::make('view')
                            ->button()->close()->url('/timesheet/projects/'.$Task->project_id.'/edit')
                            ->markAsRead(),
                    ])
                    ->toDatabase(),
            );
        }


    public function render()
    {
        return view('livewire.task-card-managment');
    }
}
