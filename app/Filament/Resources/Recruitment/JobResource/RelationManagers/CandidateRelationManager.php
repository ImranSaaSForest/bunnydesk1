<?php

namespace App\Filament\Resources\Recruitment\JobResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CandidateRelationManager extends RelationManager
{
    protected static string $relationship = 'candidate';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->label('Name'),
                TextColumn::make('created_at')->date('y/m/d'),
                SelectColumn::make('status')->options([
                                'applied' => 'applied',
                                'shortlisted' => 'shortlisted',
                                'interviewed' =>'interviewed',
                                'offer sent' =>'offer sent',
                                'contract accepted' =>'contract accepted',
                                'contract sent' =>'contract sent',
                                'offer accepted' =>'offer accepted',
                                'selected' =>'selected',
                                'rejected' =>'rejected',
            ])->disabled(true)
            // ->disabled(function($record){
            //     $edit = $record->with('job')->first();
            //     if ($record) {
            //         if($edit['job']['created_by'] == auth()->user()->id){
            //             return false;
            //         }
            //         else{
            //             return true;
            //         }
            //     }
            // }),
            // TextColumn::make('status')->visible(function(){
            //     if(auth()->user()->hasRole('Super Admin')){
            //         return true;
            //     }
            //     elseif(auth()->user()->hasRole('HR')){
            //         return true;
            //     }
            //     else{
            //         return false;
            //     }
            // }),
        ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
