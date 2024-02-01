<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\EmployeeTaxSlabResource\RelationManagers\EmployeeTaxSlabValueRelationManager;
use App\Filament\Resources\Finance\PayrunEmployeeResource\Pages;
use App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers;
use App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers\EmployeeTaxSlabRelationManager;
use App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers\EmployeeTaxSlabValueRelationManager as RelationManagersEmployeeTaxSlabValueRelationManager;
use App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers\PayrunEmployeeAllowanceRelationManager;
use App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers\PayrunEmployeeDeductionRelationManager;
use App\Filament\Resources\Finance\PayrunEmployeeResource\RelationManagers\PayrunEmployeePaymentRelationManager;
use App\Models\Payroll\PayrunEmployee;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrunEmployeeResource extends Resource
{
    protected static ?string $model = PayrunEmployee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->label('Employee')
                ->relationship('user','name')
                
                    ->required()
                    ->reactive()
                    ->disabled()
                    ->dehydrated(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payrun_employee_id'),
                Tables\Columns\TextColumn::make('total_working_hours'),
                Tables\Columns\TextColumn::make('total_paid_leave'),
                Tables\Columns\TextColumn::make('total_unpaid_leave'),
                Tables\Columns\TextColumn::make('gross_salary'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PayrunEmployeeAllowanceRelationManager::class,
            PayrunEmployeeDeductionRelationManager::class,
            RelationManagersEmployeeTaxSlabValueRelationManager::class,
            PayrunEmployeePaymentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrunEmployees::route('/'),
            'create' => Pages\CreatePayrunEmployee::route('/create'),
            'view' => Pages\ViewPayrunEmployee::route('/{record}'),
            'edit' => Pages\EditPayrunEmployee::route('/{record}/edit'),
        ];
    }
}
