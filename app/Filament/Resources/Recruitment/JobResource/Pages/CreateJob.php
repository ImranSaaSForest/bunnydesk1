<?php

namespace App\Filament\Resources\Recruitment\JobResource\Pages;

use App\Filament\Resources\Recruitment\JobResource;
use App\Models\Recruitment\JobJobAdditional;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Livewire\Attributes\On;

class CreateJob extends CreateRecord
{
    public $extra=[];

    protected static string $resource = JobResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        if($record->job_status == "open"){
            // $recipient = User::whereHas('roles', function ($q) {
            //     $q->where('name', 'Super Admin');
            // })->get();
            $recipient = User::where('id', $record->hiring_lead_id)->get();
            Notification::make()
                ->title('Review')
                ->body("A new job role ".$record->title." has been created")
                ->actions([
                    Action::make('view')
                        ->button()->url('/recruitment/jobs/'.$record->id.'/edit')->close()
                ])
                ->sendToDatabase($recipient);
        }       

        // foreach($this->extra as $value){
        //     foreach($value as $values){
        //         JobJobAdditional::create([
        //             'job_id' => $record->id,
        //             'job_additional_id' => $values,
        //             'required' => null,
        //         ]);
        //     }
        // }
        // foreach($this->check as $value){
        //         JobJobAdditional::create([
        //             'job_id' => $record->id,
        //             'job_additional_id' => $value,
        //             'required' => 0,
        //         ]);
        // }
        foreach ($this->stardata as $key => $subArray) {
            // If the first element of the sub-array is not in the input array, unset it
            if (!in_array($subArray[0], $this->check)) {
                unset($this->stardata[$key]);
            }
        }
        // foreach($this->check as $addrequired){
        //     foreach($this->stardata as $data){
        //         if ($data[0] == $addrequired) {
        //             JobJobAdditional::create([
        //                 'job_id' => $record->id,
        //                 'job_additional_id' => $data[0],
        //                 'required' => $data[1],
        //             ]);
        //         }   
        //         elseif($data[0] != $addrequired){
        //             JobJobAdditional::create([
        //                 'job_id' => $record->id,
        //                 'job_additional_id' => $addrequired,
        //                 'required' => 0,
        //             ]);
        //         }
        //     }
        // }
        foreach ($this->check as $addrequired) {
            // Default required value is 0
            $requiredValue = 0;
        
            // Search in stardata for a matching job_additional_id
            foreach ($this->stardata as $data) {
                if ($data[0] == $addrequired) {
                    $requiredValue = $data[1];
                    break; // Stop searching as we found the match
                }
            }
        
            // Check if a record already exists
            $jobAdditional = JobJobAdditional::where('job_id', $record->id)
                                             ->where('job_additional_id', $addrequired)
                                             ->first();
        
            if ($jobAdditional) {
                // If exists, update the required value
                $jobAdditional->required = $requiredValue;
                $jobAdditional->save();
            } else {
                // If not exists, create a new record
                JobJobAdditional::create([
                    'job_id' => $record->id,
                    'job_additional_id' => $addrequired,
                    'required' => $requiredValue,
                ]);
            }
        }


    }
    // protected function afterCreate(): void
    // {
    //     $record = $this->getRecord();
        // $recipient = User::where('id', $record->user_id)->get();
        //                 Notification::make()
        //                     ->title('A new performance goal has been added')
        //                     ->body($record->goal_description)
        //                     ->actions([
        //                         Action::make('view')
        //                             ->button()->url('/performance/performance-goals')
        //                     ])
        //                     ->sendToDatabase($recipient);
    // }
            public $check=[];

    #[On('checkbox')] 
    public function checkbox($title,$titles){
        $pair = $title;
        // array_push($this->check, $title);

        if (!in_array($pair, $this->check)) {
            array_push($this->check, $pair);
        } else {
            $key = array_search($pair, $this->check);
            if ($key !== false) {
                unset($this->check[$key]);
            }
        }
    }
    public $stardata=[];
    #[On('check')] 
    public function check($random, $number) {
        $pair = [$random, $number];
    
        if (!in_array($pair, $this->stardata)) {
            array_push($this->stardata, $pair);
        } else {
            $key = array_search($pair, $this->stardata);
            if ($key !== false) {
                unset($this->stardata[$key]);
            }
        }
    }
    // protected function beforeCreate(): void
    // {
        
    //     dd($this->check,$this->stardata);
        
    // }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        array_push($this->extra, $data['technologies']);

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

