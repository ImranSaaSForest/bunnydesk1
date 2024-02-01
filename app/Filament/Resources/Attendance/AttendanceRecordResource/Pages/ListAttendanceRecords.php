<?php

namespace App\Filament\Resources\Attendance\AttendanceRecordResource\Pages;

use App\Filament\Resources\Attendance\AttendanceRecordResource;
use App\Models\Attendance\AttendanceRecord;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceRecords extends ListRecords
{
    protected static string $resource = AttendanceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
             
        ];
    }
}
