<?php

namespace App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers;

use App\Models\Employee\Employee;
use App\Models\Payroll\EmployeeTaxSlabValue;
use App\Models\Payroll\PayrunEmployee;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeTaxSlabValueRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeTaxSlabValue';

    public function form(Form $form): Form
    {
        return $form
        ->schema([

            TextInput::make('start')
            ->default(function () {
                $TaxId=$this->ownerRecord->id;
                $isStart=EmployeeTaxSlabValue::where('payrun_employee_id',$TaxId)->where('start',0)->get()->count();
                if($isStart==0){
                    return 0;
                }else{
                    $endValue=EmployeeTaxSlabValue::where('payrun_employee_id',$TaxId)->max('end');
                   return  $endValue+1;
                }
            })->numeric()
            ->disabled(
                function ($state) {
                    $TaxId=$this->ownerRecord->id;
                    $isStart=EmployeeTaxSlabValue::where('payrun_employee_id',$TaxId)->where('start',0)->get()->count();
                    if($isStart==0){
                        return true;
                    }
                    elseif($state==0){
                        return true;

                    }else{
                   return false;
                    }
                }
            )
            ->dehydrated(
            )

            ->required(),
            Select::make('cal_range')
            ->options([
                'To'=>'To',
                'And Above'=>'And Above'
            ])
            ->rules([
                fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                    if ($get('end') && $value === 'And Above') {
                        $fail("You cannot use And Above if you have end value.");
                    }
                    if (!$get('end') && $value === 'To') {
                        $fail("You cannot use To if you don't have end value.");
                    }
                },
            ])
            ->default('To')
            ->label('Condition')
            ->required(),
            TextInput::make('end')
            ->numeric()
            ->gt('start'),
            TextInput::make('fixed_amount')
            ->numeric(),

            TextInput::make('percentage')
            ->numeric()
            ->default(0)
            ->suffixIcon('antdesign-percentage-o'),
            TextInput::make('description')
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payrun_employee_id')
            ->columns([
                TextColumn::make('start'),
                TextColumn::make('cal_range')
                ->label('Condition'),
                TextColumn::make('end'),
                TextColumn::make('fixed_amount')
                ->description(function($record){
                    $userId=PayrunEmployee::find($record->payrun_employee_id);
                    if($userId){
                    $company = Employee::where('user_id', $userId->user_id)->with('company')->get();
            //company currency
                $companyCurrency=$company?$company[0]->company->currency:'';
               return $companyCurrency;}
                })
                ->default(0),
                TextColumn::make('percentage'),

                TextColumn::make('description')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
