<?php

namespace App\Filament\Resources\TimeOff;

use App\Filament\Resources\TimeOff\WorkWeekResource\Pages;
use App\Filament\Resources\TimeOff\WorkWeekResource\RelationManagers;
use App\Models\TimeOff\WorkWeek;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Component\Yaml\Inline;

class WorkWeekResource extends Resource
{
    protected static ?string $model = WorkWeek::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

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

                Forms\Components\Select::make('week_start_day')
                    ->options([
                        'Sunday' => 'Sunday',
                        'Monday' => 'Monday',
                        'Tudesday' => 'Tudesday',
                        'wednesday' => 'wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',

                    ]),

                ]),

            ]),

                Forms\Components\Card::make()
                ->schema([
                  Forms\Components\Repeater::make('workWeekDays')
                                ->relationship()
                                ->schema([
                                    Forms\Components\Select::make('day')
                                    ->options([
                                        'Sunday' => 'Sunday',
                                        'Monday' => 'Monday',
                                        'Tudesday' => 'Tudesday',
                                        'wednesday' => 'wednesday',
                                        'Thursday' => 'Thursday',
                                        'Friday' => 'Friday',
                                        'Saturday' => 'Saturday',

                                        ]),


                                    Forms\Components\TimePicker::make('start_time')
                                    ->seconds(false)
                                    ->withoutSeconds()
                                    ->displayFormat('h:i A')
                                    ->timezone(config('app.timezone')),

                                    Forms\Components\TimePicker::make('end_time')
                                    ->seconds(false)
                                    ->withoutSeconds()
                                    ->displayFormat('h:i A')
                                    ->timezone(config('app.timezone'))

                                ])
                                ->columns(3)
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

                Tables\Columns\TextColumn::make('week_start_day')
                ->sortable()
                ->searchable()
                ->toggleable(),

                Tables\Columns\ToggleColumn::make('is_active')
                ->label('Status')
                ->sortable()
                ->searchable()
                ->toggleable(),

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
            'index' => Pages\ListWorkWeeks::route('/'),
            'create' => Pages\CreateWorkWeek::route('/create'),
            'edit' => Pages\EditWorkWeek::route('/{record}/edit'),
        ];
    }
}
