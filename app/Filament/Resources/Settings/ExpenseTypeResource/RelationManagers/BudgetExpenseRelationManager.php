<?php

namespace App\Filament\Resources\Settings\ExpenseTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetExpenseRelationManager extends RelationManager
{
    protected static string $relationship = 'budgetExpense';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('budget_id')
                ->relationship('budget', 'name')
                ->required(),
                TextInput::make('limit')
                ->numeric()
                ->required(),
                Toggle::make('auto_approved')
                ->required()
                ->default(true)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('budget_id')
            ->columns([
                TextColumn::make('budget.name'),
                TextColumn::make('limit'),
                ToggleColumn::make('auto_approved')
                ,            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit Budget Expense')
                ,
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Budget Expense')
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
