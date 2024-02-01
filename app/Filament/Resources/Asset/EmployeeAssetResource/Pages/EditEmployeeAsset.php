<?php

namespace App\Filament\Resources\Asset\EmployeeAssetResource\Pages;

use App\Filament\Resources\Asset\EmployeeAssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeAsset extends EditRecord
{
    protected static string $resource = EmployeeAssetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
