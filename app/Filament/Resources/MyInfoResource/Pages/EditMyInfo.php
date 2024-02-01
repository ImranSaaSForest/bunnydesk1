<?php

namespace App\Filament\Resources\MyInfoResource\Pages;

use App\Filament\Resources\MyInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMyInfo extends EditRecord
{
    protected static string $resource = MyInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
