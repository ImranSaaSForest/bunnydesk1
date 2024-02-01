<?php

namespace App\Filament\Resources\Chat\ChatRoomResource\Pages;

use App\Filament\Resources\Chat\ChatRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChatRooms extends ListRecords
{
    protected static string $resource = ChatRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
