<?php

namespace App\Filament\Resources\Recruitment;

use App\Filament\Resources\Recruitment\CandidateResource\Pages;
// use App\Filament\Resources\Recruitment\CandidateResource\RelationManagers;
use App\Models\Employee\EmployeeType;
use App\Models\Recruitment\Candidate;
use App\Models\Recruitment\CandidateAdditional;
use App\Models\Recruitment\Job;
use Dflydev\DotAccessData\Data;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Split;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use PhpParser\Node\Stmt\Label;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('id'),
                TextColumn::make('first_name')->label('First Name'),
                TextColumn::make('last_name')->label('Last Name'),
                TextColumn::make('Hiring Category')->default(function($record){
                    $employee_type_id=Job::where('id',$record->job_id)->value('employee_type_id');
                    $job_type=EmployeeType::where('id',$employee_type_id)->value('name');
                    return  $job_type;
                }),
                TextColumn::make('job.title')->searchable(),
                TextColumn::make('staus')->Label('Status')->default(function($record){
                        return $record->status;
                })->hidden(function($record){
                    if(auth()->user()->hasRole('Super Admin')|| auth()->user()->hasRole('HR')){
                        return true;
                    };
                }),
                SelectColumn::make('status')->label('Status')->searchable()
                ->rules(['required'])
                ->visible(function(){
                    if(auth()->user()->hasRole('Super Admin')||auth()->user()->hasRole('HR')){
                        return true;
                    };
                })
                ->options(function ($record)
                {

                    $type = Job::with('employeeType')->find($record->job_id);
                    if($type->employeeType->name == "Contract"){
                        return[
                            'applied' => 'Applied',
                            'screened' => 'Screened',
                            'contract accepted' =>'Contract Accepted',
                            'contract sent' =>'Contract Sent',
                            'on_hold' =>'On Hold',
                            'not_qualified' =>'Not Qualified',
                            'joined' =>'Joined',

                        ];
                        }
                        elseif($type->employeeType->name == "Full-time" ){
                            return[
                                'applied' => 'Applied',
                                'screened' => 'Screened',
                                'offer sent' =>'Offer sent',
                                'offer accepted' =>'Offer Accepted',
                                'on_hold' =>'On Hold',
                                'joined' =>'Joined',
                            ];
                        }
                        else{
                            return[
                                'applied' => 'Applied',
                                'screened' => 'Screened',
                                'on_hold' =>'On Hold',
                                'not_qualified' =>'Not Qualified',
                                'joined' =>'Joined',
                            ];
                        }

                }),

            ])
            ->filters([
                SelectFilter::make('job')
                 ->relationship('job', 'title')
                 ->searchable()
                 ->preload(),

                SelectFilter::make('status')
                ->options([
                    'applied' => 'applied',
                    'screened' => 'Screened',
                    'offer sent' =>'offer sent',
                    'contract accepted' =>'contract accepted',
                    'contract sent' =>'contract sent',
                    'offer accepted' =>'offer accepted',
                    'joined' =>'Joined',
                    'on_hold' =>'On Hold',
                    'not_qualified' =>'Not Qualified',
                ])
            ])
            ->recordUrl(
                function(Model $records){
                    return route('filament.admin.resources.recruitment.candidates.detail',['record'=>serialize($records->id)]);
                })
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCandidates::route('/'),
            'detail' => Pages\CandidateDetail::route('/detail'),
            // 'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\ViewCandidate::route('/{record}'),
            // 'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }
}
