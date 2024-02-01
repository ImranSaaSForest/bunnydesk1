<?php

namespace App\Filament\Resources\TimeOff\PendingRequestResource\Pages;

use App\Filament\Resources\TimeOff\PendingRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendingRequest extends EditRecord
{
    protected static string $resource = PendingRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
