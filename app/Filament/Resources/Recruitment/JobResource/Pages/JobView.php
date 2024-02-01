<?php

namespace App\Filament\Resources\Recruitment\JobResource\Pages;

use App\Filament\Resources\Recruitment\JobResource;
use App\Models\Recruitment\Candidate;
use App\Models\Recruitment\Job;
use Filament\Infolists\Components\Section;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use PragmaRX\Countries\Package\Services\Countries;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\SelectColumn;

class JobView extends Page  implements HasForms, HasTable
{   
    use InteractsWithTable;
    use InteractsWithForms;
    public $random;
    public $viewjob;
    public $jobid;
    protected static string $resource = JobResource::class;

    protected static string $view = 'filament.resources.recruitment.job-resource.pages.job-view';

    public function mount(){
        $this->random = unserialize($_GET['random']);
        $this->viewjob = Job::find($this->random);
        // dd($this->viewjob);
        $this->jobid = $this->random;
    }
    public function productInfolist(Infolist $infolist): Infolist
    {   
        if(isset($_GET['random'])){
        $this->random = unserialize($_GET['random']);
        $this->jobid = $this->random;
    }
        return $infolist
            ->record($this->viewjob)
            // ->record(function())
            ->schema([
                Section::make('')->schema([
                TextEntry::make('title')->label('Job Title'),
                TextEntry::make('hiring.name'),
                TextEntry::make('onboardingIist.title')->label('Assign Onboarding List'),
                TextEntry::make('Designation.name')->label('Position'),
                TextEntry::make('employeeType.name'),
                TextEntry::make('Company.name'),
                TextEntry::make('job_status'),
                TextEntry::make('country')->formatStateUsing(function ( $state){
                    $countries = new Countries();

                  $country= $countries->where('cca3', $state);
                //   dd($country);
if($country){
                    return $country[$state]->name->common;}
                }
            ),
                            TextEntry::make('province'),
                            TextEntry::make('city'),
                            TextEntry::make('postal_code'),
                            TextEntry::make('salary'),
                            TextEntry::make('interview_date'),
                            TextEntry::make('approvedBy.name'),
                            TextEntry::make('description')->columnSpanFull()
                            ])->columns(2)
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
        ->heading('Applied Candidates')
            ->query(Candidate::where('job_id',$this->jobid))
            ->columns([
                TextColumn::make('first_name')->label('Name'),
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
                ])->disabled(function($record){
                    if(auth()->user()->hasRole('Super Admin')){
                        return true;
                    }
                    elseif(auth()->user()->hasRole('HR') ){
                        $random = $record->with('job')->first();
                       if($random->job->hiring_lead_id == auth()->user()->id){
                            return false;
                       }
                       else{
                            return true;
                       }
                    }
                    else{
                        return false;
                    }
                }),
            ])
            ->filters([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
