<?php

namespace App\Filament\Resources\Chat\ChatRoomResource\Pages;

use App\Filament\Resources\Chat\ChatRoomResource;
use Filament\Resources\Pages\Page;

class Chat extends Page
{
    protected static string $resource = ChatRoomResource::class;
    protected static ?string $title = '';
    protected static string $view = 'filament.resources.chat.chat-room-resource.pages.chat';
}
