<?php

namespace App\Filament\Resources\DailyUpdate;

use App\Filament\Resources\DailyUpdate\QuestionResource\Pages;
use App\Filament\Resources\DailyUpdate\QuestionResource\RelationManagers;
use App\Models\DailyUpdate\Question;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('What question do you want to ask?'),
                        Radio::make('status')
                            ->label('How often do you want to ask?')
                            ->options([
                                'Daily On' => 'Daily On',
                                'Once a Week' => 'Once a Week',
                                'Every other week' => 'Every other week',
                                'Once a month on the first' => 'Once a month on the first'
                            ]),
                        TimePicker::make('time')
                            ->label('At what time of the day?'),
                        Select::make('user_id')
                            ->label('Who do you want to ask?')
                            ->multiple()
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('frequency'),
                TextColumn::make('start_time'),
                TextColumn::make('end_time'),
                TextColumn::make('day.day'),
                TextColumn::make('taskUser.users.name'),
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\AddQuestion::route('/{record}/edit'),
            'question' => Pages\AddQuestion::route('/question'),
        ];
    }
}
