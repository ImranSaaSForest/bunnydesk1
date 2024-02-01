<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\OverTimeRateResource\Pages;
use App\Filament\Resources\Settings\OverTimeRateResource\RelationManagers;
use App\Models\Payroll\OverTimeRate;
use App\Models\Payroll\WorkTime;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OverTimeRateResource extends Resource
{
    protected static ?string $model = OverTimeRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Card::make([
                    TextInput::make('name')
                    ->maxLength(255)

                    ->required()
                    ->label('Overtime Rate Name')
                    ->helperText('Enter the Overtime Rate for Employee Levels'),


                    TextInput::make('percentage')
                    ->prefix('Salary +')
                    ->required()

                ->numeric()
                    ->suffixIcon('antdesign-percentage-o'),

                    Section::make('Work Time')
                    ->relationship('workTime')
                    ->schema([



                    Select::make('per')
                     ->required()
                    ->options([
                        'day'=>'day',
                        // 'week'=>'week'
                    ])
                    ->default('day')
                    ->disabled()
                    ->dehydrated()
                    // ->rules([
                    //     function () {
                    //         return function (string $attribute, $value, Closure $fail) {
                    //             $weekCount=WorkTime::where('per','week')->count();
                    //             $dayCount=WorkTime::where('per','day')->count();
                    //             if ($value=='week' && $weekCount>0) {
                    //                 $fail('You cannot add more than 1 record for week');
                    //             }elseif( $value=='day' && $dayCount>0){
                    //                 $fail('You cannot add more than 1 record for day');

                    //             }
                    //         };
                    //     },
                    // ])
                    ,
                    TextInput::make('allowed_hour')
                    ->numeric()
                     ->required(),
                    TextInput::make('description')
                    ])
                    ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name'),
                TextColumn::make('percentage'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            // RelationManagers\WorkTimeRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOverTimeRates::route('/'),
            'create' => Pages\CreateOverTimeRate::route('/create'),
            'edit' => Pages\EditOverTimeRate::route('/{record}/edit'),
        ];
    }
}
