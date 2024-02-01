<?php

namespace App\Filament\Resources\TimeOff\LeaveResource\Pages;

use App\Filament\Resources\TimeOff\LeaveResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaves extends ListRecords
{
    protected static string $resource = LeaveResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
