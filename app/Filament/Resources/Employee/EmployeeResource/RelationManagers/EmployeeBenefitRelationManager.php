<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

use Filament\Forms;
use Filament\Tables;
use Squire\Models\Currency;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeBenefitRelationManager extends RelationManager
{
    protected static string $relationship = 'benefit';
    protected static ?string $title = 'Benefit';
    protected static ?string $recordTitleAttribute = 'name';
    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('benefit_id')->relationship("benefit","name")->required()
                ->createOptionForm([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                    Forms\Components\Grid::make(2)
                    ->schema([
                    Forms\Components\Select::make('currency')
                    ->options(Currency::all()->pluck('name', 'id'))

                        ->searchable()
                        ->getSearchResultsUsing(fn (string $query) => Currency::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                        ->getOptionLabelUsing(fn ($value): ?string => Currency::find($value)?->getAttribute('name'))
                        ->required(),
                    Forms\Components\TextInput::make('amount')->required(),
                    ]),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('benefit.name')->label('Name')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('benefit.description')->label('Desc')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('benefit.currency')->label('Currency')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('benefit.amount')->label('Amount')->searchable()->sortable()->toggleable(),
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
