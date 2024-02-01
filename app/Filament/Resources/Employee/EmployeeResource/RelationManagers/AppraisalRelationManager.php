<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use App\Models\Performance\PerformanceGoal;
use Filament\Forms;

use Filament\Tables;


class AppraisalRelationManager extends RelationManager
{
    protected static string $relationship = 'appraisals';

    protected static ?string $recordTitleAttribute = 'degree';

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('total_score'),
                Textarea::make('final_comments')->columnSpanFull(),
                Select::make('status')
                ->default('Draft')
                ->options([
                    'Draft' => 'Draft',
                    'Published' => 'Published',
                ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('total_score')->searchable()->sortable()->toggleable(),
                TextColumn::make('final_comments')->searchable()->sortable()->toggleable(),
                TextColumn::make('status')->searchable()->sortable()->toggleable(),
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
