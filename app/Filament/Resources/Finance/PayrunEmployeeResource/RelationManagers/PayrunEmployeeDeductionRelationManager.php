<?php

namespace App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers;

use App\Models\Employee\Employee;
use App\Models\Payroll\PayrunEmployee;
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

class PayrunEmployeeDeductionRelationManager extends RelationManager
{
    protected static string $relationship = 'payrunEmployeeDeduction';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('payrun_employee_id')
                ->relationship('payrunEmployee','payrun_employee_id')
                ->required(),
                Select::make('deduction_id')
                ->relationship('deduction','name')
            ,
                TextInput::make('occurrence')
                ->required()
                ->numeric(),

                Select::make('frequency')
                    ->options([
                        'daily' => 'daily',
                        'weekly' => 'weekly',
                         'monthly' => 'monthly'
                        ])
                    ->required(),

                    TextInput::make('amount')
                    ->numeric()
                    ->default(0),
                    TextInput::make('percentage')
                    ->numeric()
                    ->default(0)
                        ->suffixIcon('antdesign-percentage-o'),
                        Toggle::make('is_fixed')
                    ->label('Fixed')

                        ->default(true),
                        Toggle::make('before_tax')
                        ->default(true),
                        Toggle::make('include_in_payrun')
                ->inline(false)
                ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payrun_employee_id')
            ->columns([
                TextColumn::make('payrunEmployee.payrun_employee_id'),
                TextColumn::make('deduction.name'),
                TextColumn::make('occurrence'),
                // ToggleColumn::make('include_in_payrun'),
                TextColumn::make('frequency'),
                TextColumn::make('amount')
                ->description(function($record){
                    $userId=PayrunEmployee::find($record->payrun_employee_id);
                    if($userId){
                    $company = Employee::where('user_id', $userId->user_id)->with('company')->get();
            //company currency
                $companyCurrency=$company?$company[0]->company->currency:'';
               return $companyCurrency;}
                }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
