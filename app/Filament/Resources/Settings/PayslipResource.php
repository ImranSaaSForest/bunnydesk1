<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\PayslipResource\Pages;
use App\Filament\Resources\Settings\PayslipResource\RelationManagers;
use App\Models\Payroll\PayrollPayslipTemplate;
use App\Models\Settings\Payslip;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayslipResource extends Resource
{
    protected static ?string $model = PayrollPayslipTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                ->maxLength(255)

                ->required(),
                Select::make('company_id')
                ->relationship('company', 'name')
                ->required(),
                Textarea::make('description')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name'),
                TextColumn::make('company.name'),
                TextColumn::make('description')
                ->words(2)
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
                // Tables\Actions\CreateAction::make()
                // ->label('New Template'),
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
            'index' => Pages\ListPayslips::route('/'),
            'create' => Pages\PayslipTemplate::route('/create'),
            'edit' => Pages\PayslipTemplate::route('/{record}/edit'),
        ];
    }
}
