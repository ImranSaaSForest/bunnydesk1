<?php

namespace App\Filament\Resources\Finance\BudgetResource\RelationManagers;

use App\Models\Employee\JobInfo;
use App\Models\Finance\BudgetExpense;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReimbursementRequestRelationManager extends RelationManager
{
    protected static string $relationship = 'reimbursementRequest';
    protected static $userTeam;


    public function form(Form $form): Form
    {
        self::$userTeam = JobInfo::where('user_id', auth()->id())->pluck('team_id')[0];
        //GET ONLY EXPENSE TYPE THAT ARE ASSIGNED TO THE USER'S TEAM
        $expense = BudgetExpense::withWhereHas('budget', function ($budget) {
            $budget->withWhereHas('budgetTeam', function ($budgetTeam) {
                $budgetTeam->where('team_id', self::$userTeam);
            });
        })->with('expenseType')->get();
        $expenseTypes = [];
        if (count($expense) != 0) {
            $expenseTypes = $expense[0]->expenseType->pluck('name', 'id')->toArray();
        }
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->required(),
                    Select::make('budget_expense_id')
                        ->label('Budget Expense')
                        ->options(
                            $expenseTypes
                        )
                        ->required(),
                    // Select::make('status')
                    //     ->options([
                    //         'pending' => 'Pending',
                    //         'rejected' => 'Rejected',
                    //         'approved' => 'Approved'
                    //     ])
                    //     ->required(),
                    Forms\Components\FileUpload::make('attachment'),

                    TextInput::make('description'),
                    TextInput::make('amount')
                        ->required()
                        ->numeric(),
                    Textarea::make('reason'),
                ])
                    ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('budget_expense_id')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('budgetExpense.expenseType.name'),
                TextColumn::make('status'),
                TextColumn::make('amount'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'rejected' => 'Rejected',
                        'approved' => 'Approved'
                    ])
                // ->attribute('status_id')
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit Reimbursement Request')

                ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement')),

                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Reimbursement Request')

                ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement')),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ->hidden(!auth()->user()->hasPermissionTo('Manage Reimbursement')),

                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }
}
