<?php

namespace App\Filament\Resources\Timesheet\TaskResource\Pages;

use App\Filament\Resources\Timesheet\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->disabled(function($record){
                $id=$record['user_id'];
                if($id){
                   if(auth()->id()!=$id){
                    return true;
                   }
                }
            }),
        ];
    }
}
