<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NoteRelationManager extends RelationManager
{
    protected static string $relationship = 'Notes';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                ->required()->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description'),
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
