<?php

namespace App\Filament\Resources\Timesheet\TimesheetResource\Pages;

use App\Filament\Resources\Timesheet\TimesheetResource;
use App\Models\Timesheet\TimesheetApproval;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;


class EditTimesheet extends EditRecord
{
    protected static string $resource = TimesheetResource::class;

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {

    //     $approvalData = [
    //         'status' => $data['status'],
    //         'comments' => $data['comments']
    //     ];

    //     // Ensure that Supervisor does not change the status in timesheets table
    //     if (auth()->user()->hasRole('Supervisor')) {
    //         unset($data['status']);
    //     }

    //     // First update the Timesheet
    //     $record->update($data);

    //     // Create or update a TimesheetApproval record for the updated Timesheet
    //     TimesheetApproval::updateOrCreate(
    //         ['timesheet_id' => $record->id, 'user_id' => auth()->user()->id],
    //         $approvalData
    //     );

    //     // If the approval status is 'rejected', set the Timesheet status to 'rejected' as well
    //     if ($approvalData['status'] === 'rejected') {
    //         $record->update(['status' => 'rejected']);
    //     }

    //     return $record;
    // }


    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
