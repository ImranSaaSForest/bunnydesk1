<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\PayrollPolicyResource\Pages;
use App\Filament\Resources\Settings\PayrollPolicyResource\RelationManagers;
use App\Models\Payroll\PayrollPolicy;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayrollPolicyResource extends Resource
{
    protected static ?string $model = PayrollPolicy::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Card::make([
                    TextInput::make('name')
                    ->maxLength(255)

                    ->required(),
                    Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required()
                    ->reactive(),
                    DatePicker::make('policy_from'),
                    Select::make('tax_slab_id')
                    ->relationship('taxSlab', 'name')
                    ->required(),
                    Select::make('over_time_rate_id')
                    ->relationship('overTimeRate', 'name')
                    ->required(),
                    Select::make('social_security_id')
                    ->relationship('socialSecurity', 'name')
                    ->required(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name'),
                TextColumn::make('company.name'),
                TextColumn::make('taxSlab.name'),
                TextColumn::make('overTimeRate.name'),
                TextColumn::make('socialSecurity.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\PayrollPolicyAllowanceRelationManager::class,
            RelationManagers\PayrollPolicyDeductionRelationManager::class,
            RelationManagers\UserPayrollPolicyRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrollPolicies::route('/'),
            'create' => Pages\CreatePayrollPolicy::route('/create'),
            'edit' => Pages\EditPayrollPolicy::route('/{record}/edit'),
        ];
    }
}
