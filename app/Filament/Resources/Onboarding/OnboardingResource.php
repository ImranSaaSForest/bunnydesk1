<?php

namespace App\Filament\Resources\Onboarding;

use App\Filament\Resources\Onboarding\OnboardingResource\Pages;
use App\Filament\Resources\Onboarding\OnboardingResource\RelationManagers;
use App\Models\Onboarding\EmployeeOnboarding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OnboardingResource extends Resource
{
    protected static ?string $model = EmployeeOnboarding::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationLabel = 'Onboarding';

    protected static ?string $label = 'Assign Onboarding List';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->relationship('user','name')
                ->label(' Select Employee'),

                Forms\Components\Select::make('onboarding_list_id')
                ->relationship('onboardingList','title')
                ->label(' Select OnboardingList'),
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

                Tables\Columns\TextColumn::make('onboardingList.title')
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
            'index' => Pages\ManageOnboardings::route('/'),
        ];
    }
}
