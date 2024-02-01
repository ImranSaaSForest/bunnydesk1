<?php

namespace App\Filament\Resources\Attendance\AttendanceRecordResource\Pages;

use App\Filament\Resources\Attendance\AttendanceRecordResource;
use App\Models\Attendance\AttendanceRecord;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceRecord extends EditRecord
{
    protected static string $resource = AttendanceRecordResource::class;



    // protected static string $view = 'filament.resources.attendance.attendance-record-resource.pages.attendance-list';




    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\DeleteAction::make(),
    //     ];
    // }

//     public $attendance;
//     public $previousRecords;
    
//     public function mount(int | string $record): void
//     {
        
// // dd(AttendanceRecord::find($record));
//         // $this->form->fill($record->toArray());
//         // $this->form->fill();
//         // Fetch the current record
//         $this->attendance = AttendanceRecord::with('user')->where('id', $record)->get();
    
//         // Check if any records were found
//         if ($this->attendance->isNotEmpty()) {
//             // Get the user ID from the current record
//             $userId = $this->attendance[0]->user->id;
    
//             // Fetch all previous records of the same user
//             $this->previousRecords = AttendanceRecord::with('user')
//                 ->where('user_id', $userId)
//                 ->where('id', '<=', $record)
//                 ->get();
    
//             // Now $previousRecords contains all previous and current records of the same user
//             // dd($previousRecords);
    
//             $this->record = $this->resolveRecord($record);
//         }
//          else {
//             // Handle the case where no records were found for the given ID
//             // You might want to show an error message or take appropriate action
//         }

//     }

//     public function edit($id){
        

//         return  redirect()->to('/attendance/attendance-records/'.$id.'/edit');
//     }
    

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['in'] && $data['out']) {
            $in = Carbon::parse($data['in']);
            $out = Carbon::parse($data['out']);
            $data['total_hours'] = $in->diffInHours($out);

        }

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');

    }

    // protected function getSaveFormAction(): \Filament\Actions\Action
    // {
    //     return parent::getSaveFormAction()
    //         ->hidden(function (): bool {
    
             
    //             return true;
    //         });
    // }

    protected function getFormActions(): array
    {
        return [];
    }
    
}
