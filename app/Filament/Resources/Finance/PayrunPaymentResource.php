<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\PayrunPaymentResource\Pages;
use App\Filament\Resources\Finance\PayrunPaymentResource\RelationManagers;
use App\Models\Attendance\AttendanceRecord;
use App\Models\Employee\Employee;
use App\Models\Finance\PayrunPayment;
use App\Models\Payroll\PayrollPolicy;
use App\Models\Payroll\Payrun;
use App\Models\Payroll\PayrunEmployee;
use App\Models\Payroll\PayrunEmployeeAllowance;
use App\Models\Payroll\EmployeeTaxSlab;
use App\Models\Payroll\PayrunEmployeeDeduction;
use App\Models\Payroll\PayrunEmployeePayment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class PayrunPaymentResource extends Resource
{
    protected static ?string $model = PayrunEmployeePayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('payrun_employee_id')
                    ->relationship('payrunEmployee', 'payrun_employee_id')
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {

                        $payrunEmployeeId = $state;
                        $taxable = 0;
                        $amount = 0;
                        $monthTaxable=0;
                        $monthTax=0;
                        $employeeContributionAmount=0;
                        $employeeContribution=0;
                        $employerContribution=0;
                        $workmanPercentage=0;
                        $workmanAmount=0;
                        $netPay=0;
                        $grossSalary = PayrunEmployee::where('id', $payrunEmployeeId)->get() ? PayrunEmployee::where('id', $payrunEmployeeId)->get()[0]->gross_salary : 0;
                        $amount = $grossSalary;
                        // get allowance for employee
                        $allowances = PayrunEmployeeAllowance::where('payrun_employee_id', $payrunEmployeeId)->get();
                        $deductions = PayrunEmployeeDeduction::where('payrun_employee_id', $payrunEmployeeId)->get();
                        $payrunId = PayrunEmployee::find($payrunEmployeeId)->payrun_id;
                        $payrun = Payrun::find($payrunId);

                        $attendanceRecords = AttendanceRecord::where('user_id', PayrunEmployee::find($payrunEmployeeId)->user_id)->get();
                        $company = Employee::where('user_id', PayrunEmployee::find($payrunEmployeeId)->user_id)->with('company')->get();
                        $companyCurrency=$company?$company[0]->company->currency:'';
                        if ($payrunId) {
                            $PayrollPolicy = PayrollPolicy::where('id', Payrun::find($payrunId)->payroll_policy_id)->with('socialSecurity')->get();
                            if ($PayrollPolicy) {
                                // get taxslab values
                                $taxSlabValues = EmployeeTaxSlab::where('payrun_employee_id', $payrunEmployeeId)->with('employeeTaxSlabValue')->get();

                                $taxSlabValues = !$taxSlabValues->isEmpty()?$taxSlabValues[0]->employeeTaxSlabValue:'';

                                if($taxSlabValues){
                                // calculate allowance if before tax
                                foreach ($allowances as $allowance) {
                                    // check before tax
                                    if ($allowance->before_tax) {
                                        // check frequency and payment interval monthly
                                        if ($allowance->frequency == 'monthly' && $payrun->payment_interval == 'monthly') {
                                           // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence; $occurrence++) {

                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($allowance->frequency == 'weekly' && $payrun->payment_interval == 'weekly') {

                                            // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence; $occurrence++) {

                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($allowance->frequency == 'weekly' && $payrun->payment_interval == 'biweekly') {
                                            // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence * 2; $occurrence++) {
                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        }
                                        if ($allowance->frequency == 'daily') {
                                            // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence * $attendanceRecords->count(); $occurrence++) {
                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        }
                                    }
                                }

                                // calculate deduction if before tax
                                foreach ($deductions as $deduction) {
                                    if ($deduction->before_tax) {
                                        // check frequency and payment interval monthly
                                        if ($deduction->frequency == 'monthly' && $payrun->payment_interval == 'monthly') {
                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence; $occurrence++) {

                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($deduction->frequency == 'weekly' && $payrun->payment_interval == 'weekly') {

                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence; $occurrence++) {

                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($deduction->frequency == 'weekly' && $payrun->payment_interval == 'biweekly') {
                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence * 2; $occurrence++) {
                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        }
                                        if ($deduction->frequency == 'daily') {
                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence * $attendanceRecords->count(); $occurrence++) {
                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        }
                                    }
                                }
                                // calculate taxable income
                                $anualTaxable = ($amount*self::convertCurrency('LRD',$companyCurrency,1)) * 12;
                                $anualTax = 0;
                                // calculate tax
                                foreach ($taxSlabValues as $taxSlabValue) {
                                    // dd($taxSlabValue->cal_range == 'To');
                                    //if calclate range to
                                    if ($taxSlabValue->cal_range == 'To') {

                                        if ($anualTaxable > $taxSlabValue->start &&  $anualTaxable < $taxSlabValue->end) {
                                            $taxDeduct = 0;
                                            if ($taxSlabValue->start == 0) {
                                                $taxDeduct = 0;
                                            } else {
                                                $taxDeduct = $taxSlabValue->start - 1;
                                            }

                                            $anualTax = $taxSlabValue->percentage >= 0 ? ($taxSlabValue->percentage / 100) * ($anualTaxable - $taxDeduct) + $taxSlabValue->fixed_amount : 0;
                                        }
                                    } elseif ($taxSlabValue->cal_range == 'And Above') {
                                        if ($anualTaxable > $taxSlabValue->start) {

                                            $taxDeduct = 0;
                                            if ($taxSlabValue->start == 0) {
                                                $taxDeduct = 0;
                                            } else {
                                                $taxDeduct = $taxSlabValue->start - 1;
                                            }
                                            $anualTax = $taxSlabValue->percentage >= 0 ? ($taxSlabValue->percentage / 100) * ($anualTaxable - $taxDeduct) + $taxSlabValue->fixed_amount : 0;
                                            // dd($anualTaxable,$anualTax,$taxSlabValue->fixed_amount);
                                            // dd(($taxSlabValue->percentage / 100) * ($anualTaxable - $taxDeduct));

                                        }
                                    }
                                }
                                $monthTaxable = $anualTaxable / 12;
                                $monthTax = ($anualTax / 12)/self::convertCurrency('LRD',$companyCurrency,1);
                                // calculate allowance if after tax
                                foreach ($allowances as $allowance) {
                                    if (!$allowance->before_tax) {
                                        // check frequency and payment interval monthly
                                        if ($allowance->frequency == 'monthly' && $payrun->payment_interval == 'monthly') {
                                            // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence; $occurrence++) {

                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($allowance->frequency == 'weekly' && $payrun->payment_interval == 'weekly') {

                                            // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence; $occurrence++) {

                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($allowance->frequency == 'weekly' && $payrun->payment_interval == 'biweekly') {
                                            // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence * 2; $occurrence++) {
                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        }
                                        if ($allowance->frequency == 'daily') {
                                            // looping based on the allowance occurrence
                                            for ($occurrence = 1; $occurrence <= $allowance->occurrence * $attendanceRecords->count(); $occurrence++) {
                                                $allowancePercentage = $allowance->percentage;
                                                $allowanceAmount = $allowance->amount;
                                                $amount = $amount + $allowanceAmount + (($allowancePercentage / 100) * $grossSalary);
                                            }
                                        }
                                    }
                                }
                                // calculate deduction if after tax
                                foreach ($deductions as $deduction) {
                                    if (!$deduction->before_tax) {
                                        // check frequency and payment interval monthly
                                        if ($deduction->frequency == 'monthly' && $payrun->payment_interval == 'monthly') {
                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence; $occurrence++) {

                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($deduction->frequency == 'weekly' && $payrun->payment_interval == 'weekly') {

                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence; $occurrence++) {

                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        } elseif ($deduction->frequency == 'weekly' && $payrun->payment_interval == 'biweekly') {
                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence * 2; $occurrence++) {
                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        }
                                        if ($deduction->frequency == 'daily') {
                                            // looping based on the deduction occurrence
                                            for ($occurrence = 1; $occurrence <= $deduction->occurrence * $attendanceRecords->count(); $occurrence++) {
                                                $deductionPercentage = $deduction->percentage;
                                                $deductionAmount = $deduction->amount;
                                                $amount = $amount - $deductionAmount - (($deductionPercentage / 100) * $grossSalary);
                                            }
                                        }
                                    }
                                }

                                // social security
                                $socialSecurity = $PayrollPolicy[0]->socialSecurity;
                                $employeeContribution = $socialSecurity->employee_contribution;
                                $employeeContributionAmount = ($employeeContribution / 100) * $grossSalary;
                                $employerContribution = $socialSecurity->employer_contribution;
                                $employerContributionAmount = ($employerContribution / 100) * $grossSalary;

                                //work man
                                $workmanPercentage = 2;
                                $workmanAmount = (2 / 100) * $grossSalary;

                                // net pay
                                $netPay = $monthTaxable - $monthTax - $employeeContributionAmount - $workmanAmount;
                            }
                            else{
                                Notification::make()
                                ->title('Please create tax slab for this employee')
                                ->success()
                                ->send();
                            }
                            $set('taxable', round($monthTaxable));
                        $set('tax', round($monthTax));
                        $set('employee_social_security_percentage', round($employeeContribution));
                        $set('employee_social_security', round($employeeContributionAmount));
                        $set('employer_social_security_percentage', round($employerContribution));
                        $set('workman_percentage', round($workmanPercentage));
                        $set('workman', round($workmanAmount));
                        $set('net_pay', round($netPay));
                        }
                        }




                    })
                    ->required(),
                // employee
                TextInput::make('employee_social_security_percentage')
                    ->disabled()
                    ->dehydrated()
                    ->label('Employee social security percentage')
                    ->default(0)
                    ->numeric()
                    ->required(),
                TextInput::make('employee_social_security')
                    ->disabled()
                    ->dehydrated()
                    ->label('Employee social security')

                    ->default(0)
                    ->numeric()
                    ->required(),
                // employer
                TextInput::make('employer_social_security_percentage')
                    ->disabled()
                    ->dehydrated()
                    ->label('Employer social security percentage')
                    ->default(0)
                    ->numeric()
                    ->required(),
                TextInput::make('employer_social_security')
                    ->disabled()
                    ->dehydrated()
                    ->label('Employer social security')

                    ->default(0)
                    ->numeric()
                    ->required(),

                TextInput::make('taxable')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required(),
                TextInput::make('tax')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required(),
                TextInput::make('workman_percentage')
                    ->disabled()
                    ->dehydrated()
                    ->default(0)
                    ->numeric()
                    ->required(),
                TextInput::make('workman')
                    ->disabled()
                    ->dehydrated()
                    ->default(0)
                    ->numeric()
                    ->required(),
                TextInput::make('net_pay')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required(),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payrunEmployee.payrun_employee_id'),
                TextColumn::make('employee_social_security_percentage')
                    ->label('Employee SS percentage'),
                TextColumn::make('taxable'),
                TextColumn::make('tax'),
                TextColumn::make('workman_percentage'),
                TextColumn::make('net_pay'),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrunPayments::route('/'),
            'create' => Pages\CreatePayrunPayment::route('/create'),
            // 'edit' => Pages\EditPayrunPayment::route('/{record}/edit'),
        ];
    }

    public static function convertCurrency($want,$have,$amount){

        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://currency-converter-by-api-ninjas.p.rapidapi.com/v1/convertcurrency?have='.$have.'&want='.$want.'&amount='.$amount, [
            'headers' => [
                'X-RapidAPI-Host' => 'currency-converter-by-api-ninjas.p.rapidapi.com',
                'X-RapidAPI-Key' => '0188cfa695msh42c309b4263b606p13bf7djsn29da431c7e94',
            ],
        ]);

        $convertedAmount = $response->getBody()->getContents();
        return json_decode($convertedAmount)->new_amount;
    }
}
