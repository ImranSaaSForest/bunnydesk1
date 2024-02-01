<?php

namespace App\Filament\Resources\Schedule;

use App\Filament\Resources\Schedule\ScheduleResource\Pages;
use App\Filament\Resources\Schedule\ScheduleResource\RelationManagers;
use App\Models\Schedule\Schedule;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Forms\Components\Select::make('created_by')
                            ->relationship('users', 'name')
                            ->label('Created By')
                            ->disabled(),
                    ]),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('event_date')
                    ->required(),
                Forms\Components\TimePicker::make('event_time')
                    ->required()
                    ->seconds(false),
                Forms\Components\TimePicker::make('notify_at')
                    ->seconds(false),
                // ->datalist([
                //     '09:00',
                //     '09:30',
                //     '10:00',
                //     '10:30',
                //     '11:00',
                //     '11:30',
                //     '12:00',
                //     '12:30',
                //     '13:00',
                //     '13:30',
                //     '14:00',
                //     '14:30',
                //     '15:00',
                //     '15:30',
                //     '16:00',
                //     '16:30',
                //     '17:00',
                //     '17:30',
                //     '18:00',
                //     '18:30',
                //     '19:00',
                //     '19:30',
                //     '20:00',
                //     '20:30',
                //     '21:00'
                // ]),

                // Forms\Components\DateTimePicker::make('notification_time'),
                Forms\Components\Textarea::make('about')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('created_by', auth()->id())->orWhereHas('scheduleWith', function ($query) {
                return $query->where('user_id', auth()->id());
            }))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Host')
                    ->badge(),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_time')
                    ->dateTime('h:i A'),
                // Tables\Columns\TextColumn::make('notify_at')
                //     ->dateTime('h:i A')
                //     ->label('Notification At'),
                Tables\Columns\TextColumn::make('scheduleWith.users.name')
                    ->badge()
                    ->label('Participants'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(function ($record) {
                    if (auth()->user()->id == $record->created_by) {
                        return false;
                    } else {
                        return true;
                    }
                }),
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
