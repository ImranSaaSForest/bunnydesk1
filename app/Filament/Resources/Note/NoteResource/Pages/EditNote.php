<?php

namespace App\Filament\Resources\Note\NoteResource\Pages;

use App\Filament\Resources\Note\NoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNote extends EditRecord
{
    protected static string $resource = NoteResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
