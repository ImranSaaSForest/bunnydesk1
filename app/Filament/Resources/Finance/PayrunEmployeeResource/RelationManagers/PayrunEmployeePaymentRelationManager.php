<?php

namespace App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers;

use App\Filament\Resources\Settings\PayslipResource\Pages\PayslipTemplate;
use App\Models\Employee\Employee;
use App\Models\Payroll\PayrunEmployee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class PayrunEmployeePaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'payrunEmployeePayment';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('payrun_employee_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payrun_employee_id')
            ->columns([
                TextColumn::make('payrunEmployee.payrun_employee_id'),
                TextColumn::make('employee_social_security_percentage')
                ->label('Employee SS percentage'),
                TextColumn::make('employer_social_security_percentage')
                    ->label('Employer SS percentage'),
                TextColumn::make('taxable')
                ->description(function($record){
                    $userId=PayrunEmployee::find($record->payrun_employee_id);
                    if($userId){
                    $company = Employee::where('user_id', $userId->user_id)->with('company')->get();
            //company currency
                $companyCurrency=$company?$company[0]->company->currency:'';
               return $companyCurrency;}
                }),
                TextColumn::make('tax')
                ->description(function($record){
                    $userId=PayrunEmployee::find($record->payrun_employee_id);
                    if($userId){
                    $company = Employee::where('user_id', $userId->user_id)->with('company')->get();
            //company currency
                $companyCurrency=$company?$company[0]->company->currency:'';
               return $companyCurrency;}
                }),
                TextColumn::make('workman_percentage'),
                TextColumn::make('net_pay')
                ->description(function($record){
                    $userId=PayrunEmployee::find($record->payrun_employee_id);
                    if($userId){
                    $company = Employee::where('user_id', $userId->user_id)->with('company')->get();
            //company currency
                $companyCurrency=$company?$company[0]->company->currency:'';
               return $companyCurrency;
            }
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([

                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
