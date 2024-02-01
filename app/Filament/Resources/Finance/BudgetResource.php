<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\BudgetResource\Pages;
use App\Filament\Resources\Finance\BudgetResource\RelationManagers;
use App\Models\Finance\Budget;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
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

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
                // Tables\Actions\EditAction::make()
                // ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement')),
                Tables\Actions\DeleteAction::make()
                ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement'))
                ,
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make()
                // ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement'))
                // ,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BudgetExpenseRelationManager::class,
            RelationManagers\ReimbursementRequestRelationManager::class,


        ];
    }

    public static function getPages(): array
    {

        return [
            'index' => Pages\ListBudgets::route('/'),
            // 'create' => Pages\CreateBudget::route('/create'),
            'view' => Pages\ViewBudget::route('/{record}'),
            // 'edit' => Pages\EditBudget::route('/{record}/edit'),
        ];
    }
}
