<?php

namespace App\Filament\Resources\DailyUpdate;

use App\Filament\Resources\DailyUpdate\DailyWorkResource\Pages;
use App\Filament\Resources\DailyUpdate\DailyWorkResource\RelationManagers;
use App\Models\DailyUpdate\DailyWork;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyWorkResource extends Resource
{
    protected static ?string $model = DailyWork::class;

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
            'index' => Pages\Work::route('/'),
            'edit' => Pages\EditDailyWork::route('/{record}/create'),
        ];
    }
}
