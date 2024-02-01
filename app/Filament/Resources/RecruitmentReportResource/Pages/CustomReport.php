<?php

namespace App\Filament\Resources\RecruitmentReportResource\Pages;

use App\Filament\Resources\RecruitmentReportResource;
use App\Models\Company\Company;
use App\Models\Recruitment\Candidate;
use App\Models\Recruitment\Job;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use PhpParser\Node\Stmt\Label;

class CustomReport extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = RecruitmentReportResource::class;

    protected static string $view = 'filament.resources.recruitment-report-resource.pages.custom-report';

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill();
    }
    public $company;
    public $status;
    public $monthlyReport;
    public $positionReport;

    public function form(Form $form): Form
    {
        return $form

            ->statePath('data')
            ->schema([
                // section::make('Job Reports')
                //     ->schema([
                        Select::make('company')
                            ->options(Company::pluck('name', 'id'))
                            ->label('Company')
                            ->afterStateUpdated(function (?string $state) {
                                $this->company = $state;
                                $this->dispatch('post-created1', companyId: $state);
                            })
                            // ->default(1)
                            ->live(),

                        Select::make('status')
                            ->options([
                                'applied' => 'Applied',
                                'screening' => 'Screened',
                                'offer sent' => 'Offer Sent',
                                'offer accepted' => 'Offer Accepted',
                                'on_hold' => 'On Hold',
                            ])
                            ->afterStateUpdated(function (?string $state) {
                                $this->status = $state;
                                $companyId = Candidate::where('job_id', $this->company)->get();
                                // $this->monthlyReport = Candidate::where()
                                $this->dispatch('post-created2', state: $state);
                                // dd($state);
                            })
                            // ->default('applied')
                            ->label('Hiring Status')
                            ->live(),
                    // ])
                    ])->columns(1);


    }

    protected function getFooterWidgets(): array
    {
        return [

            // RecruitmentMonthlyChart::make([
            //     'company'=>$this->company
            // ]),
            // RecruitmentPositionChart::make([
            //     //
            // ]),
        ];
    }
}

