<?php

namespace App\Filament\Resources\Attendance\AttendanceRecordResource\Pages;

use App\Filament\Resources\Attendance\AttendanceRecordResource;
use App\Models\Attendance\AttendanceRecord;
use Carbon\Carbon;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class AttendanceList extends Page


{

    use InteractsWithRecord;

    protected static string $resource = AttendanceRecordResource::class;


   
    public $lists;
    public $attendance;
    public $previousRecords;
    
    public function mount(int | string $record): void
    {
        // Fetch the current record
        $this->attendance = AttendanceRecord::with('user')->where('id', $record)->get();
    
        // Check if any records were found
        if ($this->attendance->isNotEmpty()) {
            // Get the user ID from the current record
            $userId = $this->attendance[0]->user->id;
    
            // Fetch all previous records of the same user
            $this->previousRecords = AttendanceRecord::with('user')
                ->where('user_id', $userId)
                ->where('id', '<=', $record)
                ->get();
    
            // Now $previousRecords contains all previous and current records of the same user
            // dd($previousRecords);
    
            // Continue with your logic...
            $this->record = $this->resolveRecord($record);
        } else {
            // Handle the case where no records were found for the given ID
            // You might want to show an error message or take appropriate action
        }

    }

    public function edit($id){
        

        return  redirect()->to('/attendance/attendance-records/'.$id.'/edit');
    }
        
}
