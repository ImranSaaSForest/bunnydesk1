<?php

namespace App\Filament\Resources\DailyUpdate;

use App\Filament\Resources\DailyUpdate\DailyWorkResource\Pages\Work;
use App\Filament\Resources\DailyUpdate\DateResource\Pages;
use App\Filament\Resources\DailyUpdate\DateResource\RelationManagers;
use App\Models\DailyUpdate\Date;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DateResource extends Resource
{
    protected static ?string $model = Date::class;

    protected static ?string $navigationLabel = 'Daily Status';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
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
            'index' => Pages\ListDates::route('/'),
            'create' => Pages\CreateDate::route('/create'),
            'edit' => Work::route('/{record}/edit'),
            'popup' => Pages\DailyWorkPopup::route('/popup'),
        ];
    }
}
