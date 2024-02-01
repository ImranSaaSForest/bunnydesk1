<?php

namespace App\Filament\Resources\Settings\BudgetResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetManagerRelationManager extends RelationManager
{
    protected static string $relationship = 'budgetManager';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                    Select::make('manager_id')
                        ->relationship('manager', 'name')
                        ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('budget_id')
            ->columns([
                Tables\Columns\TextColumn::make('manager.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit Manager')
                 ,
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Manager')
                ,
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
}
