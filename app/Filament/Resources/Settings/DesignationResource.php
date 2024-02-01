<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\DesignationResource\Pages;
use App\Filament\Resources\Settings\DesignationResource\RelationManagers;
use App\Models\Company\Designation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DesignationResource extends Resource
{
    protected static ?string $model = Designation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationGroup = 'Company Settings';

    protected static ?string $navigationLabel = 'Designation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                        ->label('Designation Name')
                        ->required()
                        ->maxLength(255),
                        Forms\Components\Select::make('department_id')
                        ->label('Associated Name')
                        ->required()
                            ->relationship('department', 'name'),
                     
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Designation')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                ->label('Asssociated Department')
                ->sortable()
                    ->searchable(),
            
            ])
            ->filters([
                //filter for department
                Tables\Filters\SelectFilter::make('department')->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('designation')->relationship('department', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDesignations::route('/'),
        ];
    }

    
}
