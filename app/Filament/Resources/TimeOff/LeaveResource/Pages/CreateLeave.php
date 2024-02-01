<?php

namespace App\Filament\Resources\TimeOff\LeaveResource\Pages;

use App\Filament\Resources\TimeOff\LeaveResource;
use App\Models\Role;
use App\Models\User;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;



    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        if($data['apply_for'] == 'myself') {
            $data['user_id'] = auth()->id();
        }
        if(auth()->user()->hasRole('Staff')){
            $datasend = User::whereHas('roles', function ($q) {
                $q->where('name', 'Supervisor');
            })->get();
            $random = User::with('jobInfo')->find(auth()->id());

            $report = User::find($random->jobInfo->report_to);
            // dd($random['jobInfo']['report_to']);

            if(count($datasend) > 0){
                foreach ($datasend as $value) {
                    $users = User::find($value->id);
                    Notification::make()
                    ->title('A new leave has been requested')
                    ->actions([
                        ActionsAction::make('view')
                        ->button()->url('/Request')
                        ->close()
                    ])
                    ->sendToDatabase($users);
                }

                Notification::make()
                    ->title('A new leave has been requested')
                    ->actions([
                        ActionsAction::make('view')
                        ->button()->url('/Request')
                        ->close()
                    ])
                    ->sendToDatabase($report);
            }

            // if($datasend[0]->id){
            //     Notification::make()
            //         ->title('A new leave has been requested')
            //         ->actions([
            //             ActionsAction::make('view')
            //             ->button()->url('/Request')
            //             ->close()
            //         ])
            //         ->sendToDatabase($datasend);

            //         Notification::make()
            //         ->title('A new leave has been requested')
            //         ->actions([
            //             ActionsAction::make('view')
            //             ->button()->url('/Request')
            //             ->close()
            //         ])
            //         ->sendToDatabase($report);
            //     }
                }
                

        
        if(auth()->user()->hasRole('HR')){
                $data['status'] = 'approved';
               }
        if(auth()->user()->hasRole('Super Admin')){
            $data['status'] = 'approved';
        }
        if(auth()->user()->hasRole('Supervisor')){
                $data['status'] = 'forwarded';
               }
        return $data;
    }
    protected function beforeCreate(): void
    {
        $recipient = User::whereHas('roles', function ($q) {
            $q->where('name', 'HR');
        })->get();
        if (auth()->user()->hasRole('Supervisor')) {
            if(count($recipient) > 0){
                foreach ($recipient as $value) {
                    $users = User::find($value->id);
                    Notification::make()
                ->title('A new leave has been requested')
                ->actions([
                    Action::make('view')
                    ->button()->url('/Request')
                    ->close()
                ])
                ->sendToDatabase($users);
                }
            }
            // if($recipient[0]->id){
            // Notification::make()
            //     ->title('A new leave has been requested')
            //     ->actions([
            //         Action::make('view')
            //         ->button()->url('/Request')
            //         ->close()
            //     ])
            //     ->sendToDatabase($recipient);
            // }
            }
    }


    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //    
    // }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
