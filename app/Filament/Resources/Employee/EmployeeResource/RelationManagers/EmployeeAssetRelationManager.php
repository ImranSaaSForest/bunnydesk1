<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use App\Models\Asset\Asset;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

use Filament\Forms;
use Filament\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeAssetRelationManager extends RelationManager
{
    protected static string $relationship = 'employeeAsset';
    protected static ?string $title = 'Asset';
    protected static ?string $label = 'Assign Asset';

    protected static ?string $recordTitleAttribute = 'asset_id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\Select::make('asset_id')
                ->relationship('asset', 'name')->required()

                ->createOptionForm([
                Forms\Components\Grid::make(2)
                  ->schema([

                    Forms\Components\Select::make('company_id')
                    ->relationship('company','name')->required(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                               ]),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                    Forms\Components\Grid::make(2)
                  ->schema([
                Forms\Components\TextInput::make('value')->label('Amount'),
                Forms\Components\DatePicker::make('purchase_date'),
                  ]),

                        ])

                ]);



    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([





                Tables\Columns\TextColumn::make('asset.name')->label('Name'),
                Tables\Columns\TextColumn::make('asset.description')->label('Description'),
                // Tables\Columns\TextColumn::make('asset.value')->label('Value'),
                // Tables\Columns\TextColumn::make('asset.purchase_date')
                //     ->date()->label('Purchase date'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make() ->visible(function(){
                    if(auth()->user()->hasPermissionTo('Employee Profiles')){
                        return true;
                    }
                }),
            ])
            ->actions([
                
                Tables\Actions\DeleteAction::make() ->visible(function(){
                    if(auth()->user()->hasPermissionTo('Employee Profiles')){
                        return true;
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make() ->visible(function(){
                    if(auth()->user()->hasPermissionTo('Employee Profiles')){
                        return true;
                    }
                }),
            ]);
    }
}
