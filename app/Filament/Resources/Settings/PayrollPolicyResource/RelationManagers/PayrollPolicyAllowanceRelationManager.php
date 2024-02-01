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

class PayrollPolicyAllowanceRelationManager extends RelationManager
{
    protected static string $relationship = 'payrollPolicyAllowance';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('allowance_id')
                ->relationship('allowance','name')
                ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payroll_policy_id')
            ->columns([
                Tables\Columns\TextColumn::make('payrollPolicy.name'),
                Tables\Columns\TextColumn::make('allowance.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit Allowance')

                ,
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Allowance')
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
