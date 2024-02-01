<?php

namespace App\Filament\Resources\Chat;

use App\Filament\Resources\Chat\ChatRoomResource\Pages;
use App\Filament\Resources\Chat\ChatRoomResource\Pages\Chat;
use App\Filament\Resources\Chat\ChatRoomResource\RelationManagers;

use App\Models\livechat\ChatRoom as LivechatChatRoom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatRoomResource extends Resource
{
    protected static ?string $model = LivechatChatRoom::class;
  
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Chat::route('/'),
            'create' => Pages\CreateChatRoom::route('/create'),
            'edit' => Pages\EditChatRoom::route('/{record}/edit'),
        ];
    }
}
