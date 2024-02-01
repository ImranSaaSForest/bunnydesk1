<?php

namespace App\Filament\Resources\DailyUpdate\DateResource\Pages;

use App\Filament\Resources\DailyUpdate\DateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDate extends EditRecord
{
    protected static string $resource = DateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
