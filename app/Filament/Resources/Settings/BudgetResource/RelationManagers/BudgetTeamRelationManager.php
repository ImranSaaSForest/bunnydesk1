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

class BudgetTeamRelationManager extends RelationManager
{
    protected static string $relationship = 'budgetTeam';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('team_id')
                        ->relationship('team', 'name')
                        ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('budget_id')
            ->columns([
                Tables\Columns\TextColumn::make('team.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit Team')
                ,
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Team')
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
