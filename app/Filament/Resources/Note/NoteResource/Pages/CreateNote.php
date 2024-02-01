<?php

namespace App\Filament\Resources\Note\NoteResource\Pages;

use App\Filament\Resources\Note\NoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;
}
