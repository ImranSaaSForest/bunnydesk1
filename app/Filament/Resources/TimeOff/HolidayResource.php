<?php

namespace App\Filament\Resources\TimeOff;

use App\Filament\Resources\TimeOff\HolidayResource\Pages;
use App\Filament\Resources\TimeOff\HolidayResource\RelationManagers;
use App\Models\TimeOff\Holiday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'TimeOff Settings';


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
        Forms\Components\Card::make()
             ->schema([
                    Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                    ->required(),
                ]),

                Forms\Components\Textarea::make('description')
                ->required(),

            ]),


            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Repeater::make('holidayDates')
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                    ->required(),

                                    Forms\Components\DatePicker::make('holiday_date')
                                    ->required(),


                                    Forms\Components\Toggle::make('optional')
                                    ->inline(false)

                                ])
                                ->columns(2)
               ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable()
                ->toggleable(),

                Tables\Columns\TextColumn::make('description')
                ->sortable()
                ->searchable()
                ->toggleable(),

                Tables\Columns\ToggleColumn::make('is_active')
                ->label('Status')
                ->sortable()
                ->searchable()
                ->toggleable()
                // ->afterStateUpdated(function(){

                // })

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }
}
