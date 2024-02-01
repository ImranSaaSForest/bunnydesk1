<?php

namespace App\Filament\Resources\Employee;

use App\Filament\Resources\Employee\EmployeeStatusResource\Pages;
use App\Filament\Resources\Employee\EmployeeStatusResource\RelationManagers;
use App\Models\Employee\EmployeeStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeStatusResource extends Resource
{
    protected static ?string $model = EmployeeStatus::class;


    protected static ?string $navigationLabel = 'Employee Status';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Employee Info Settings';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(250),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEmployeeStatuses::route('/'),
        ];
    }    
}
