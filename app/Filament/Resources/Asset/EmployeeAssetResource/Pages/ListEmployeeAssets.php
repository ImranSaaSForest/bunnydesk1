<?php

namespace App\Filament\Resources\Asset\EmployeeAssetResource\Pages;

use App\Filament\Resources\Asset\EmployeeAssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAssets extends ListRecords
{
    protected static string $resource = EmployeeAssetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
