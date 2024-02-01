<?php

namespace App\Filament\Resources\Offboarding;

use App\Filament\Resources\Offboarding\OffboardingResource\Pages;
use App\Filament\Resources\Offboarding\OffboardingResource\RelationManagers;
use App\Models\Offboarding\EmployeeOffboarding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OffboardingResource extends Resource
{
    protected static ?string $model = EmployeeOffboarding::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Offboarding';

     protected static ?string $label = 'Assign Offboarding List';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                    Forms\Components\Select::make('user_id')
                    ->relationship('user','name')
                    ->label(' Select Employee'),

                    Forms\Components\Select::make('offboarding_list_id')
                    ->relationship('offboardingList','title')
                    ->label(' Select OffboardingList'),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                ->label('Employee Name')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('offboardingList.title')
                ->label('List Name')
                ->sortable()
                ->searchable(),

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
            'index' => Pages\ManageOffboardings::route('/'),
        ];
    }
}
