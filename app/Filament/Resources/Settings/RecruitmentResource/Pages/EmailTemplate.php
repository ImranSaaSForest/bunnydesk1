<?php

namespace App\Filament\Resources\Settings\RecruitmentResource\Pages;

use App\Filament\Resources\Settings\RecruitmentResource;
use App\Models\Recruitment\OfferLetterTemplate;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Rule;

class EmailTemplate extends Page
{
    protected static string $resource = RecruitmentResource::class;

    protected static string $view = 'filament.resources.settings.recruitment-resource.pages.email-template';

    public $emailVariables=['Candidate First Name','Candidate Last Name','Sender First Name','Sender Mobile','Job Location','Sender Last Name','Job Opening Name','Job Department','Hiring Lead Job Title'];
    public $companies;
    #[Rule('required')]
    public $templateName;
    #[Rule('required')]
    public $description;
    #[Rule('required')]
    public $content;
    public $record;
    public $currentAction;

    public function mount()
    {
        // SET DATA ON EDIT ACTION
        if ($this->record) {
            $this->currentAction = 'edit';
            $template = OfferLetterTemplate::where('id', $this->record)->get();
            $this->templateName = $template[0]->name;
            $this->description = $template[0]->description;
            $this->content = $template[0]->content;
        }
        // $this->emailVariables = PayrollVariable::all() ? PayrollVariable::all() : collect();
        // $this->companies = Company::all();
    }
    public function createTemplate()
    {
        $this->validate();

        if (OfferLetterTemplate::where('id', $this->record)->get()->count() == 0) {

             OfferLetterTemplate::create([
                'name' => $this->templateName,
                'description' => $this->description,
                'content' => $this->content
            ]);
        }


        else

        {
           //UPDATE TEMPLATE

            OfferLetterTemplate::where('id', $this->record)->update([
                'name' => $this->templateName,
                'description' => $this->description,
                'content' => $this->content
            ]);
        }


        return redirect()->to('/settings/recruitments');





    }



}
