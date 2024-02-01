<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\BudgetResource\Pages;
use App\Filament\Resources\Settings\BudgetResource\RelationManagers;
use App\Models\Finance\Budget;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                    ->maxLength(255)

                        ->required(),
                    Select::make('company_id')
                        ->relationship('company', 'name')
                        ->required(),

                    TextInput::make('description'),
                    DatePicker::make('start_date')
                    ->seconds(false)
                        ->required(),
                    Select::make('frequency')
                        ->options([
                            'monthly' => 'Monthly',
                            'quarterly' => 'Quarterly',
                            'half yearly' => 'Half yearly',
                            'yearly' => 'Yearly'
                        ])
                        ->required()

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('name'),
               TextColumn::make('company.name'),
               TextColumn::make('start_date'),
               TextColumn::make('frequency'),
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
            RelationManagers\BudgetManagerRelationManager::class,
            RelationManagers\BudgetTeamRelationManager::class,
            RelationManagers\BudgetExpenseRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBudgets::route('/'),
            'create' => Pages\CreateBudget::route('/create'),
            'edit' => Pages\EditBudget::route('/{record}/edit'),
        ];
    }
}
