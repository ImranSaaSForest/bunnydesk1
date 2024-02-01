<?php

namespace App\Filament\Resources\Asset;

use App\Filament\Resources\Asset\AssetResource\Pages\CreateAsset;
use App\Filament\Resources\Asset\AssetResource\Pages\EditAsset;
use App\Filament\Resources\Asset\AssetResource\Pages\ListAssets;
use App\Models\Asset\Asset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;



class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Select::make('company_id')
                    ->relationship('company','name')
                    ->required(),
               TextInput::make('name')
                    ->required()
                    ->maxLength(255),
               Textarea::make('description')
                    ->maxLength(65535),
               TextInput::make('value'),
               DatePicker::make('purchase_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name'),
                TextColumn::make('name'),
                TextColumn::make('description'),
                TextColumn::make('value'),
                TextColumn::make('purchase_date')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()->visible(
                    function(){
                        if(auth()->user()->hasPermissionTo('assets Edit')){
                                    return true;
                                }
                    }
                ),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->visible(
                    function(){
                        if(auth()->user()->hasPermissionTo('assets Create')){
                                    return true;
                                }
                    }
                ),
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
            'index' => ListAssets::route('/'),
            'create' => CreateAsset::route('/create'),
            'edit' => EditAsset::route('/{record}/edit'),
        ];
    }
}
