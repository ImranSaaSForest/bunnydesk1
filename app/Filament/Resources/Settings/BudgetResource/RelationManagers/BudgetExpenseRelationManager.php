<?php

namespace App\Filament\Resources\Settings\BudgetResource\RelationManagers;

use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Finance\Budget;
use App\Models\Finance\BudgetExpense;
use App\Models\Finance\ReimbursementRequest;
use Carbon\Carbon;
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
                Select::make('expense_type_id')
                ->relationship('expenseType', 'name')
                ->required(),
                TextInput::make('limit')
                ->numeric()
                ->required(),
                // Toggle::make('auto_approved')
                // ->required()
                // ->default(true)

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('budget_id')
            ->columns([
                TextColumn::make('expenseType.name'),
                TextColumn::make('limit')
                ->money(function(){
                    return Company::find(Employee::where('user_id',auth()->id())->pluck('company_id')[0])->pluck('currency')[0];
                }),
                // ToggleColumn::make('auto_approved'),
                TextColumn::make('Claimed Amount')
                ->default(function(BudgetExpense $record){

                    $budgetExpense = BudgetExpense::with('budget')->find($record->id);
                    if($budgetExpense){

                        $budget=Budget::find($budgetExpense->budget->id);


                        if(Carbon::create($budget->last_reset_date)<Carbon::now()){

                                // $budgetExpenseLimit=$budgetExpense->limit;
                                $budgetExpenseFrequency =$budget->frequency;
                                $budgetExpenseStartDate =$budget->start_date;
                                if ($budgetExpenseFrequency == 'monthly') {
                                    Budget::find($budgetExpense->budget->id)->update([
                                        'last_reset_date'=>Carbon::create($budgetExpenseStartDate)->addMonths(1)
                                    ]);

                                }
                                if ($budgetExpenseFrequency == 'yearly') {
                                   Budget::find($budgetExpense->budget->id)->update([
                                        'last_reset_date'=>Carbon::create($budgetExpenseStartDate)->addYears(1)
                                    ]);

                                }
                                if ($budgetExpenseFrequency == 'half yearly') {
                                    Budget::find($budgetExpense->budget->id)->update([
                                        'last_reset_date'=>Carbon::create($budgetExpenseStartDate)->addMonths(6)
                                    ]);

                                }
                                if ($budgetExpenseFrequency == 'quarterly') {
                                   Budget::find($budgetExpense->budget->id)->update([
                                        'last_reset_date'=>Carbon::create($budgetExpenseStartDate)->addMonths(3)

                                    ]);

                                }
                            }
                             // get start date
                           
                            if ($budget->frequency == 'monthly') {

                                $startDate=Carbon::create($budget->last_reset_date)->subMonths(1);

                            }
                            if ($budget->frequency == 'yearly') {

                                $startDate=Carbon::create($budget->last_reset_date)->subYears(1);

                            }
                            if ($budget->frequency == 'half yearly') {

                                $startDate=Carbon::create($budget->last_reset_date)->subMonths(6);

                            }
                            if ($budget->frequency == 'quarterly') {

                                $startDate=Carbon::create($budget->last_reset_date)->subMonths(3);

                            }
                                $totalReimbursmentAmount = ReimbursementRequest::where('budget_expense_id', $record->id)->whereIn('status', ['pending','approved'])
                                    ->whereBetween(
                                        'reimbursement_requests.created_at',
                                        [
                                            $startDate, Carbon::create(Budget::find($budgetExpense->budget->id)->last_reset_date)
                                            ]
                                    )->sum('amount');
                                    return $totalReimbursmentAmount;
                    }

                })
                ])
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
