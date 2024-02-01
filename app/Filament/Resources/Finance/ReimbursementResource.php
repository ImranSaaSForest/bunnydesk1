<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\ReimbursementResource\Pages;
use App\Filament\Resources\Finance\ReimbursementResource\RelationManagers;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Employee\JobInfo;
use App\Models\Finance\Budget;
use App\Models\Finance\BudgetExpense;
use App\Models\Finance\ExpenseType;
use App\Models\Finance\Reimbursement;
use App\Models\Finance\ReimbursementRequest;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReimbursementResource extends Resource
{
    protected static ?string $model = ReimbursementRequest::class;
    protected static $userTeam;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Reimbursement';


    public static function form(Form $form): Form
    {
        $expenseTypes=[];
        // GET USER TEAM
        $userTeam = JobInfo::where('user_id', auth()->id())->pluck('team_id')->first();
        if($userTeam){

        self::$userTeam=$userTeam;
        $company=Employee::where('user_id',auth()->id())->first();
        if($company){

        //GET ONLY EXPENSE TYPE THAT ARE ASSIGNED TO THE USER'S TEAM
        $expense = BudgetExpense::withWhereHas('budget', function ($budget) use($company) {
            $budget->where('company_id',$company->company_id)->withWhereHas('budgetTeam', function ($budgetTeam) {
                $budgetTeam->where('team_id', self::$userTeam);
            });
        })->with('expenseType')->get();
        $expenseTypes = [];
        if (count($expense) != 0) {
            $expenseTypes = $expense->pluck('expenseType.name', 'id')->toArray();

        }
    }
}



        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)

                        ->label('Reimbursement Title'),
                        // ->helperText('Enter the name of the Product'),
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

                    // TextInput::make('description'),
                    TextInput::make('amount')
                        ->rules([
                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                // Get parent budget expense record
                                $budgetExpense = BudgetExpense::with('budget')->find($get('budget_expense_id'));
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
                                $totalReimbursmentAmount = ReimbursementRequest::where('budget_expense_id', $get('budget_expense_id'))->whereIn('status', ['pending','approved'])
                                    ->whereBetween(
                                        'reimbursement_requests.created_at',
                                        [
                                            $startDate, Carbon::create(Budget::find($budgetExpense->budget->id)->last_reset_date)
                                            ]
                                    )->sum('amount');
                                    // dd($totalReimbursmentAmount);
                                if ($budgetExpenseLimit!=0 && $budgetExpenseLimit<$totalReimbursmentAmount+$value) {
                                    $fail("Expense Amount is already claimed.please contact manager");
                                }
                            }

                            },
                        ])
                        ->minValue(1)
                        ->required()
                        ->numeric(),
                    Textarea::make('reason'),
                    Forms\Components\FileUpload::make('attachment')
                    ->minSize(1)
                    ->maxSize(2000)
                    ->openable()
                    // ->acceptedFileTypes(['video/mp4', 'video/avi'])
                    // ->rules(['file', 'mimes:mp4,avi', 'max:102400'])

                    ,

                ])
                    ->columns(2)
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
                // Tables\Actions\EditAction::make()
                //     ->disabled(function (ReimbursementRequest $record) {

                //         if ($record->status == 'pending') {
                //             return false;
                //         }
                //         return true;
                //     }),

                Tables\Actions\DeleteAction::make()
                    ->disabled(function (ReimbursementRequest $record) {

                        if ($record->status == 'pending') {
                            return false;
                        }
                        return true;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $query->where('requested_by', auth()->user()->id);

        return $query;
    }
    public static function getPages(): array
    {

        return [
            'index' => Pages\ListReimbursements::route('/'),
            'create' => Pages\CreateReimbursement::route('/create')
            // 'edit' => Pages\EditReimbursement::route('/{record}/edit')
            ,
        ];
    }
}
