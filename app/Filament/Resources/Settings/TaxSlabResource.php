<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\TaxSlabResource\Pages;
use App\Filament\Resources\Settings\TaxSlabResource\RelationManagers;
use App\Models\Payroll\TaxSlab;
use App\Models\Payroll\TaxSlabValue;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
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
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Request;

class TaxSlabResource extends Resource
{
    protected static ?string $model = TaxSlab::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                    ->maxLength(255)
                        ->helperText('Enter the type of Tax Slab.')
                        ->required()
                        ->label('Tax Slab Name'),



                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
             
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
            RelationManagers\TaxSlabValueRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTaxSlabs::route('/'),
            'create' => Pages\CreateTaxSlab::route('/create'),
            'edit' => Pages\EditTaxSlab::route('/{record}/edit'),
        ];
    }
}
