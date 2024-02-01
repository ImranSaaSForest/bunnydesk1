<?php

namespace App\Filament\Resources\DailyUpdate\QuestionResource\Pages;

use App\Filament\Resources\DailyUpdate\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
