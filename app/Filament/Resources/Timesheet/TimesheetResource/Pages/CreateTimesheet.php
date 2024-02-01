<?php

namespace App\Filament\Resources\Timesheet\TimesheetResource\Pages;
use App\Models\User;
use Filament\Notifications\Notification;
use App\Filament\Resources\Timesheet\TimesheetResource;
use App\Models\Timesheet\Timesheet;
use Filament\Pages\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateTimesheet extends CreateRecord
{
    protected static string $resource = TimesheetResource::class;

    protected function afterCreate(): void{
        
            $record=Timesheet::get();
            
            $recipient = User::where('id', $record[0]->user_id)->get();

            Notification::make()
                ->title('You have been assigned a task')
                ->actions([
                    Action::make('view')
                        ->button()->url('/timesheet/timesheets')
                        ->close()
                   ])
                   ->sendToDatabase($recipient);
    }
}
