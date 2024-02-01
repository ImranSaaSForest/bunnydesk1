<?php

namespace App\Filament\Resources\Finance\PendingReimbursementRequestResource\Pages;

use App\Filament\Resources\Finance\PendingReimbursementRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendingReimbursementRequest extends EditRecord
{
    protected static string $resource = PendingReimbursementRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
