<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

use Filament\Forms;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EducationRelationManager extends RelationManager
{
    protected static string $relationship = 'Education';

    protected static ?string $recordTitleAttribute = 'degree';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('school_college')->label('College')->required()
                ->maxLength(250),
            Forms\Components\TextInput::make('degree')->required()
                ->maxLength(50),
            Forms\Components\TextInput::make('course')
                ->maxLength(50),
            Forms\Components\TextInput::make('grade')
                ->maxLength(20),
            Forms\Components\DatePicker::make('course_from')->required(),
            Forms\Components\DatePicker::make('course_to')->required(),
            Forms\Components\TextInput::make('description')
                ->maxLength(250),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('school_college')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('degree')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('course')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('grade')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('course_from')
                    ->date(),
                Tables\Columns\TextColumn::make('course_to')
                    ->date(),
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
