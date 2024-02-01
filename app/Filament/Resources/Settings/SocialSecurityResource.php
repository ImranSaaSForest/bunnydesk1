<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\SocialSecurityResource\Pages;
use App\Filament\Resources\Settings\SocialSecurityResource\RelationManagers;
use App\Models\Payroll\SocialSecurity;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocialSecurityResource extends Resource
{
    protected static ?string $model = SocialSecurity::class;

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

                    TextInput::make('employee_contribution')
                     ->numeric()
                    ->default(0)
                        ->suffixIcon('antdesign-percentage-o')
                    ->required(),
                    TextInput::make('employer_contribution')
                     ->numeric()
                    ->default(0)
                        ->suffixIcon('antdesign-percentage-o')
                    ->required(),

                    Textarea::make('description')
                    ->required(),
                    Toggle::make('before_tax')
                    ->default(true),
                ])
                ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('employee_contribution'),
                TextColumn::make('employer_contribution'),
                ToggleColumn::make('before_tax')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialSecurities::route('/'),
            'create' => Pages\CreateSocialSecurity::route('/create'),
            'edit' => Pages\EditSocialSecurity::route('/{record}/edit'),
        ];
    }
}
