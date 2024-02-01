<?php

namespace App\Filament\Resources\Settings\PayrollPolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrollPolicyDeductionRelationManager extends RelationManager
{
    protected static string $relationship = 'payrollPolicyDeduction';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('deduction_id')
                ->relationship('deduction','name')
                ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payroll_policy_id')
            ->columns([
                Tables\Columns\TextColumn::make('payrollPolicy.name'),
                Tables\Columns\TextColumn::make('deduction.name'),            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit Deduction')
                ,
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Deduction')
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
