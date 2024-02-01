<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\PendingReimbursementRequestResource\Pages;
use App\Filament\Resources\Finance\PendingReimbursementRequestResource\RelationManagers;
use App\Models\Finance\Budget;
use App\Models\Finance\BudgetExpense;
use App\Models\Finance\ReimbursementRequest;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendingReimbursementRequestResource extends Resource
{
    protected static ?string $model = ReimbursementRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('budgetExpense.expenseType.name'),
                TextColumn::make('status'),
                TextColumn::make('amount'),
                TextColumn::make('requestedBy.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view_file')
                    ->label('View File')
                    ->url(fn (Model $record): string => '/storage/' . $record->attachment)
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
                    ->visible(fn (Model $record): string => $record->attachment ? $record->attachment : ''),
                Action::make('approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')

                    ->mountUsing(fn (Forms\ComponentContainer $form) => $form->fill([
                        'status' => 'approved',
                        'comment' => 'Your reimbursement request has been approved. '
                    ]))
                    ->action(function (ReimbursementRequest $record, array $data): void {
                        $budgetExpense = BudgetExpense::with('budget')->find($record->budget_expense_id);
                                if($budgetExpense){

                                    $budgetExpenseLimit=$budgetExpense->limit;
                        $budget=Budget::find($budgetExpense->budget->id);



                        if(Carbon::create($budget->last_reset_date)<Carbon::now()){

                                // $budgetExpenseLimit=$budgetExpense->limit;
                                $budgetExpenseFrequency =$budget->frequency;
                                $budgetExpenseStartDate =$budget->start_date;
                                if ($budgetExpenseFrequency == 'monthly') {
                                    $budget=Budget::find($budgetExpense->budget->id)->update([
                                        'last_reset_date'=>Carbon::create($budgetExpenseStartDate)->addMonths(1)
                                    ]);

                                }
                                if ($budgetExpenseFrequency == 'yearly') {
                                   $budget= Budget::find($budgetExpense->budget->id)->update([
                                        'last_reset_date'=>Carbon::create($budgetExpenseStartDate)->addYears(1)
                                    ]);

                                }
                                if ($budgetExpenseFrequency == 'half yearly') {
                                    $budget=Budget::find($budgetExpense->budget->id)->update([
                                        'last_reset_date'=>Carbon::create($budgetExpenseStartDate)->addMonths(6)
                                    ]);

                                }
                                if ($budgetExpenseFrequency == 'quarterly') {
                                   $budget= Budget::find($budgetExpense->budget->id)->update([
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
                                $totalReimbursmentAmount = ReimbursementRequest::where('budget_expense_id', $record->budget_expense_id)->whereIn('status', ['approved'])
                                    ->whereBetween(
                                        'reimbursement_requests.created_at',
                                        [
                                          $startDate, Carbon::create(Budget::find($budgetExpense->budget->id)->last_reset_date)
                                            ]
                                    )->sum('amount');
                                    if ($budgetExpenseLimit!=0 && $budgetExpenseLimit<$totalReimbursmentAmount+$record->amount) {
                                    Notification::make()
                                    ->title("Budget limit is $budgetExpenseLimit. Claimed expense Amount is $totalReimbursmentAmount. You cannot approve.")
                                        ->danger()
                                    ->send();


                                }else{


                        ReimbursementRequest::find($record->id)->update([
                            'status' => 'approved',
                            'comments' => $data['comment']
                        ]);


                        $recipient = User::where('id', $record->requested_by)->get();


                        Notification::make()
                            ->title('Your reimbursement request has been approved')
                            ->body($data['comment'])
                            ->sendToDatabase($recipient);
                        }
                    }
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comments')
                            ->required(),
                    ]),
                Action::make('reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->mountUsing(fn (Forms\ComponentContainer $form) => $form->fill([
                        'status' => 'denied',
                        'comment' => 'We regret to inform you that your reimbursement request has been rejected'
                    ]))
                    ->action(function (ReimbursementRequest $record, array $data): void {
                        ReimbursementRequest::find($record->id)->update([
                            'status' => 'rejected',
                            'comments' => $data['comment']

                        ]);

                        // $record->status = 'denied';

                        // $record->save();
                        $recipient = User::where('id', $record->requested_by)->get();

                        Notification::make()
                            ->title('Your reimbursement request has been rejected')
                            ->body($data['comment'])
                            ->sendToDatabase($recipient);
                    })
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comments')
                            ->required(),
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    // public static function getNavigationBadge(): ?string
    // {

    //     return static::getModel()::withWhereHas('budgetExpense', function ($budgetExpense) {

    //         $budgetExpense->withWhereHas('budget', function ($budget) {
    //             $budget->withWhereHas('budgetManager', function ($budgetTeam) {
    //                 $budgetTeam->where('manager_id', auth()->id());
    //             });
    //         });
    //     })->where('status', 'pending')->count();
    // }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $query->withWhereHas('budgetExpense', function ($budgetExpense) {

            $budgetExpense->withWhereHas('budget', function ($budget) {
                $budget->withWhereHas('budgetManager', function ($budgetTeam) {
                    $budgetTeam->where('manager_id', auth()->id());
                });
            });
        })->where('status', 'pending');


        return $query;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendingReimbursementRequests::route('/'),
        ];
    }
}
