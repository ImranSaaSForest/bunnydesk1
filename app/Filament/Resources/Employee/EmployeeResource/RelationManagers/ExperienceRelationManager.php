<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExperienceRelationManager extends RelationManager
{
    protected static string $relationship = 'Experience';

    protected static ?string $recordTitleAttribute = 'company_name';

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                            ->maxLength(250)->required(),
                        Forms\Components\TextInput::make('designation')
                            ->maxLength(100)->required(),
                        Forms\Components\TextInput::make('salary')
                            ->maxLength(20),
                        Forms\Components\DatePicker::make('exp_from')->required(),
                        Forms\Components\DatePicker::make('exp_to')->required(),
                        Forms\Components\TextInput::make('reference_name')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('reference_phone')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('description')
                            ->maxLength(250),
            ]);
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('designation')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('salary')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('exp_from')
                    ->date(),
                Tables\Columns\TextColumn::make('exp_to')
                    ->date(),
                Tables\Columns\TextColumn::make('reference_name')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('reference_phone')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('description')->searchable()->sortable()->toggleable(),
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
                Tables\Actions\EditAction::make() ->visible(function(){
                    if(auth()->user()->hasPermissionTo('Employee Profiles')){
                        return true;
                    }
                }),
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
