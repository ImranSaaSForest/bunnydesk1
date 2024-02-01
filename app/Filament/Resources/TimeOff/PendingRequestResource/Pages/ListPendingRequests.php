<?php

namespace App\Filament\Resources\TimeOff\PendingRequestResource\Pages;

use App\Filament\Resources\TimeOff\PendingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPendingRequests extends ListRecords
{
    protected static string $resource = PendingRequestResource::class;

    protected function getActions(): array
    {
        return [
           
        ];
    }
}
