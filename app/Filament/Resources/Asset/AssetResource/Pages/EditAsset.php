<?php

namespace App\Filament\Resources\Asset\AssetResource\Pages;

use App\Filament\Resources\Asset\AssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
