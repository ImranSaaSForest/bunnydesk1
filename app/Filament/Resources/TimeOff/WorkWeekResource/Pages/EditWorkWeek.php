<?php

namespace App\Filament\Resources\TimeOff\WorkWeekResource\Pages;

use App\Filament\Resources\TimeOff\WorkWeekResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkWeek extends EditRecord
{
    protected static string $resource = WorkWeekResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
