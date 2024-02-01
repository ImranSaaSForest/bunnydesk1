<?php

namespace App\Filament\Resources\Employee\EmployeeResource\RelationManagers;

use App\Models\Performance\PerformanceGoal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;


class PerformanceGoalsRelationManager extends RelationManager
{
    protected static string $relationship = 'performanceGoals';

    protected static ?string $recordTitleAttribute = 'degree';

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('goal_description')->columnSpanFull()
                ->required(),
                Forms\Components\DatePicker::make('start_date'),
                Forms\Components\DatePicker::make('end_date'),
                Forms\Components\Select::make('status')
                ->default('not_started')
                ->options([
                    'not_started' => 'Not Started',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                ])
            ]);
    }
    // public static function canViewForRecord(Model $ownerRecord): bool
    // {
    //     return false;
    // }
    public  function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('goal_description')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('start_date')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('end_date')->searchable()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('rating_score')->label('Rating (/10)'),
                Tables\Columns\TextColumn::make('status')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make() ->visible(function(){
                //     if(auth()->user()->hasPermissionTo('Employee Profiles')){
                //         return true;
                //     }
                // }),
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
