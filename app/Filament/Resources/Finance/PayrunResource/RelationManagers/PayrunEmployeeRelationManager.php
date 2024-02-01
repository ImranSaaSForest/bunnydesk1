<?php

namespace App\Filament\Resources\Finance\PayrunResource\RelationManagers;

use App\Models\Attendance\AttendanceRecord;
use App\Models\Attendance\AttendanceType;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Employee\SalaryDetail;
use App\Models\Payroll\Payrun;
use App\Models\Payroll\PayrunEmployee;
use App\Models\Payroll\UserPayrollPolicy;
use App\Models\TimeOff\Leave;
use App\Models\TimeOff\Policy;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class PayrunEmployeeRelationManager extends RelationManager
{
    protected static string $relationship = 'payrunEmployee';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->label('Employee')
                ->options(User::whereDoesntHave('payrunEmployee', function ($query) {
                    $query->where('payrun_id', $this->ownerRecord->id);
                })->pluck('name', 'id'))
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (?string $state, Set $set, ?string $old) {
                        $payrun = $this->getOwnerRecord();
                        $payrollPolicyId = $payrun->payroll_policy_id;
                        $userId = $state;

                        $attendanceRecords = AttendanceRecord::where('user_id', $userId)->get();
                        $totalWorkHours = 0;
                        $totalPaidBreakHours = 0;
                        $totalUnPaidBreakHours = 0;

                        // TOTAL PAID LEAVE

                        $totalPaidLeave = 0;
                        $totalUnPaidLeave = 0;
                        $workingHourLimit = 0;
                        $overTimeHour = 0;
                        $leaveCounts = Leave::where('user_id', $userId)->get();
                        foreach ($leaveCounts as $leaveCount) {
                            $totalUnPaidLeave = $leaveCount->days_taken;
                        }

                        //  CALCULATE GROSS SALARY

                        $companyId = Employee::where('user_id', $userId)->get()[0]->company_id;

                        if ($companyId) {
                            $policyId = DB::table('company_policy')->where('company_id', $companyId)->get()[0]->policy_id;
                            if ($policyId) {
                                // get weekdays of a company
                                $policy = Policy::find($policyId)->with('workWeek.workWeekDays')->get();
                                if ($policy) {
                                    $weekDays = $policy[0]->workWeek->workWeekDays[0];
                                    // calculate total hours limit
                                    if ($weekDays->start_time && $weekDays->end_time) {
                                        $start = Carbon::parse($weekDays->start_time);
                                        $end = Carbon::parse($weekDays->end_time);
                                        $workingHourLimit = $start->diffInHours($end);
                                    }
                                }
                            }
                        }

                        //calculate salary per hour
                        $salaryDetail = SalaryDetail::where('user_id', $userId)->with('paymentInterval')->get();
                        $salaryPerHour = 0;
                        if ($salaryDetail) {
                            if ($salaryDetail[0]->paymentInterval) {
                                if ($salaryDetail[0]->paymentInterval->name == 'Hourly') {
                                    $salaryPerHour = $salaryDetail[0]->amount;
                                }
                                if ($salaryDetail[0]->paymentInterval->name == 'Daily') {
                                    $salaryPerHour = $salaryDetail[0]->amount / $workingHourLimit;
                                }
                                if ($salaryDetail[0]->paymentInterval->name == 'Weekly') {
                                    $salaryPerHour = $salaryDetail[0]->amount / ($workingHourLimit * 7);
                                }
                                if ($salaryDetail[0]->paymentInterval->name == 'Biweekly') {
                                    $salaryPerHour = $salaryDetail[0]->amount / ($workingHourLimit * 7 * 2);
                                }
                                if ($salaryDetail[0]->paymentInterval->name == 'Monthly') {
                                    if ($payrun->payment_interval == 'monthly') {
                                        $daysInMonth = Carbon::create($payrun->start)->daysInMonth;
                                        $salaryPerHour = $salaryDetail[0]->amount / ($workingHourLimit * $daysInMonth);
                                    } else {
                                        $daysInMonth = Carbon::create($payrun->start)->daysInMonth;
                                        $salaryPerHour = $salaryDetail[0]->amount / ($workingHourLimit * $daysInMonth);
                                    }
                                }
                                if ($salaryDetail[0]->paymentInterval->name == 'Yearly') {
                                    $daysInYear = Carbon::create($payrun->start)->daysInYear;
                                    $salaryPerHour = $salaryDetail[0]->amount / ($workingHourLimit * $daysInYear);
                                }
                            }
                        }

                        //  get overtime limit hour
                        $overTimeData = Payrun::where('id', $payrun->id)->with('payrollPolicy.overTimeRate.workTime')->get();
                        if ($overTimeData) {
                            $overTimePercentage = $overTimeData[0]->payrollPolicy->overTimeRate->percentage;
                            $overTimeLimitHour = $overTimeData[0]->payrollPolicy->overTimeRate->workTime[0]->allowed_hour;
                        }

                        // convert employee timezone into company timezone
                        foreach ($attendanceRecords as $attendanceRecord) {
                            $attendanceRecord->in =Carbon::create($attendanceRecord->in)->tz(Company::find($companyId)->timezone);
                        }

                        //TOTAL WORK HOURS
                        foreach ($attendanceRecords->where('attendance_type_id', AttendanceType::where('name', 'work')->get()[0]->id)
                            ->whereBetween(
                                'in',
                                [Carbon::create($payrun->start)->tz(Company::find($companyId)->timezone)->subDays(1),
                                Carbon::create($payrun->end)->tz(Company::find($companyId)->timezone)->addDays(1)
                                 ]
                            )->where('status', 'approved') as $attendanceRecord) {

                            // calculate overtime hour nd total hour
                            if ($attendanceRecord->total_hours > $workingHourLimit) {
                                if (($attendanceRecord->total_hours - $workingHourLimit) > $overTimeLimitHour) {
                                    $overTimeHour = $overTimeLimitHour;
                                } else {
                                    $overTimeHour = $overTimeHour + ($attendanceRecord->total_hours - $workingHourLimit);
                                }
                                $totalWorkHours = $totalWorkHours + 8;

                            } else {
                                $totalWorkHours = $totalWorkHours + $attendanceRecord->total_hours;

                            }

                        }

                        // //TOTAL PAID BREAK HOURS
                        // foreach ($attendanceRecords->where('attendance_type_id', AttendanceType::where('name', 'paid break')->get()[0]->id)
                        //     ->whereBetween(
                        //         'in',
                        //         [$payrun->start, $payrun->end]
                        //     )->where('status', 'approved') as $attendanceRecord) {
                        //     $totalPaidBreakHours = $totalPaidBreakHours + $attendanceRecord->total_hours;
                        // }

                        // //TOTAL UNPAID BREAK HOURS
                        // foreach ($attendanceRecords->where('attendance_type_id', AttendanceType::where('name', 'paid break')->get()[0]->id)
                        //     ->whereBetween(
                        //         'in',
                        //         [$payrun->start, $payrun->end]
                        //     )->where('status', 'approved') as $attendanceRecord) {
                        //     $totalUnPaidBreakHours = $totalUnPaidBreakHours + $attendanceRecord->total_hours;
                        // }

                        // working time amount
                        $workingTimeAmount = $salaryPerHour * $totalWorkHours;

                        //overtime amount
                        $overTimeAmount = $overTimeHour <= 0 ? 0 : ($overTimeHour * $salaryPerHour) + (($overTimePercentage / 100) * $salaryPerHour);

                        $unPaidLeaveAmount = $totalUnPaidLeave * 8 * $salaryPerHour;

                        // gross salary
                        $grossSalary = $workingTimeAmount + $overTimeAmount;
                        //set data
                        $set('total_working_hours', $totalWorkHours+$overTimeHour);
                        $set('total_paid_leave', $totalPaidLeave);
                        $set('total_unpaid_leave', $totalUnPaidLeave);
                        $set('gross_salary', $grossSalary < 0 ? 0 : round($grossSalary));
                        $set('payrun_employee_id', $this->generateUniqueId($userId, $payrun->id));
                    }),
                Forms\Components\TextInput::make('payrun_employee_id')
                    ->unique()
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('total_working_hours')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('total_paid_leave')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('total_unpaid_leave')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('gross_salary')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(),


            ]);
    }
    // public function generateUniqueId($empId)
    // {
    //     $payrunString = 'EMP';
    //     $lastRecord = PayrunEmployee::latest()->first();
    //     if (!$lastRecord) {
    //         // If no records exist, start with an initial value
    //         $lastId = 1;
    //     } else {
    //         // Get the ID of the last record
    //         $lastId = $lastRecord->id + 1;
    //     }
    //     return $this->getOwnerRecord()->id . $payrunString . $lastId;
    // }

     // generate unique payrun employee id
     public function generateUniqueId($empId, $payrunId)
     {
         $payrunString = 'EMP';
         return $payrunId . $payrunString . $empId;
     }
    public function table(Table $table): Table
    {
        // [
        //     'user_id',
        //     'payrun_id',
        //     'payrun_employee_id',
        //     'total_working_hours',
        //     'total_paid_leave',
        //     'total_unpaid_leave',
        //     'gross_salary',
        //     'include_in_payrun'

        // ]

        $payrollPolicyId = $this->getOwnerRecord()->payroll_policy_id;

        return $table
            ->recordTitleAttribute('payrun_id')
            ->columns([
                Tables\Columns\TextColumn::make('payrun_employee_id'),
                Tables\Columns\TextColumn::make('user.name')
                ->label('Employee'),
                Tables\Columns\TextColumn::make('total_working_hours'),
                Tables\Columns\TextColumn::make('total_paid_leave'),
                Tables\Columns\TextColumn::make('total_unpaid_leave'),
                Tables\Columns\TextColumn::make('gross_salary')
                ->description(function($record){
                    $company = Employee::where('user_id', $record->user_id)->with('company')->get();
            //company currency
                $companyCurrency=$company?$company[0]->company->currency:'';
               return $companyCurrency;
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('Refresh')
                ->after(function (PayrunEmployeeRelationManager $livewire){
                    $livewire->dispatch('refresh');
                })
                ->visible(function(){
                    $record=$this->getOwnerRecord();
                    if($record->status=='in progress'){
                        return true;
                    }
                })
                    ->color('danger'),

            ])
            ->actions([
                // Action::make('Send Payslip')
                // ->action(function ($record) {
                //     // ...
                //     return redirect()->to('/pdf/'.$record->id);
                // }),
                Tables\Actions\ViewAction::make()
                ->url(fn (PayrunEmployee $record): string => route('filament.admin.resources.finance.payrun-employees.view', $record)),
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Payrun Employee')
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
