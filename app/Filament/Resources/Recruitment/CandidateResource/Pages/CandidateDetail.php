<?php

namespace App\Filament\Resources\Recruitment\CandidateResource\Pages;

use App\Filament\Resources\Recruitment\CandidateResource;
use App\Models\Recruitment\Candidate;
use App\Models\Recruitment\CandidateAdditional;
use App\Models\Recruitment\CandidateAddress;
use App\Models\Recruitment\CandidateEducation;
use App\Models\Recruitment\CandidateNote;
use App\Models\Recruitment\CandidateVisaDetail;
use App\Models\Recruitment\Job;
use App\Models\Recruitment\OfferLetterTemplate;
use DateTime;
// use Spatie\PdfToText\Pdf;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Dompdf\FrameDecorator\Text;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Section;
use Filament\Resources\Pages\Page;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\File;

class CandidateDetail extends Page
{
    protected static string $resource = CandidateResource::class;

    protected static string $view = 'filament.resources.recruitment.candidate-resource.pages.candidate-detail';
    public $details;
    public $com;
    public function getHeading(): string
    {
        $name = Candidate::find($this->details);
        // return __(dD('d'));
        return __($name->first_name . '  ' . $name->last_name);
    }



    public  function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->com)
            ->schema([
                Section::make('Candidate Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')->label('Candidate Status'),
                        Infolists\Components\TextEntry::make('job_id.name')->label('Job')->default(function ($record) {
                            $jop = Job::find($record->job_id);
                            return $jop->title;
                        }),
                        Infolists\Components\TextEntry::make('email')->label('Email'),
                        Infolists\Components\TextEntry::make('created_at')->date('Y-m-d')->label('Applied On'),
                        Infolists\Components\TextEntry::make('pnone_number')->label('Phone Number'),
                        ImageEntry::make('photo')->default(function($record){
                            return 'storage/'.$record->photo;
                            // return 'storage/app/public/'.''.$record->photo;
                        }),
                    ])->columns(2),
                // address section
                Section::make('Address')
                    ->hidden(
                        function ($record) {
                            $candidate_address = CandidateAddress::where('candidate_id',$record->id)->get();
                            if (count($candidate_address)>0) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    )
                    ->schema([
                        Infolists\Components\TextEntry::make('candidate_address.flat_no')->label('Flat No')->visible(function ($state) {
                            if (!is_null($state[0])) {

                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_address.street_1')->label('Street 1')->visible(function ($state) {

                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_address.street_2')->label('Street 2')->visible(function ($state) {

                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_address.country')->label('Country')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_address.city')->label('City')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_address.province')->label('Province')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_address.postal_code')->label('Postal Code')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),

                    ])->columns(2),
                //address section end
                //education section
                Section::make('Education Details')
                    ->hidden(
                        function ($record) {
                            $candidate_education = CandidateEducation::where('candidate_id',$record->id)->get();
                            if (count($candidate_education)>0) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    )
                    ->schema([
                        Infolists\Components\TextEntry::make('candidate_educations.college_name')->label('College Name')->visible(function ($state) {
                            // dd($state);
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_educations.branch')->label('Branch')->visible(function ($state) {
                            // dd($state);
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_educations.highest_education')->label('Highest Education')->visible(function ($state) {

                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_educations.passed')->label('Passed Out')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_educations.grade')->label('Grade')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                    ])->columns(2),
                //educations section end
                Section::make('Additional Information')
                    ->hidden(
                        function ($record) {
                            $additional_info = CandidateAdditional::where('candidate_id',$record->id)->get();
                            if (count($additional_info)>0) {
                                if (
                                    is_null($additional_info[0]->linkedin_url) &&
                                    is_null($additional_info[0]->desired_salary) &&
                                    is_null($additional_info[0]->cover_letter) &&
                                    is_null($additional_info[0]->website_blog_portfolio) &&
                                    is_null($additional_info[0]->date_available)
                                ) {
                                    return true;
                                } else {
                                    return false;
                                }
                            } else {
                                return true;
                            }
                        }
                    )
                    ->schema([
                        Infolists\Components\TextEntry::make('candidate_additional.desired_salary')->label('Desired Salary')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_additional.referredby')->label('Referred By')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_additional.reference')->label('Reference')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),

                        Infolists\Components\TextEntry::make('candidate_additional.linkedin_url')->label('Linkedin Url')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),

                        Infolists\Components\TextEntry::make('candidate_additional.website_blog_portfolio')->label('Website Blog Portfolio')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        
                        Infolists\Components\TextEntry::make('candidate_additional.cover_letter')->label('Cover Letter')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),

                    ])->columns(2),
                // Visa validation section
                Section::make('Visa Details')
                    ->hidden(
                        function ($record)
                        {
                            $candidate_visa = CandidateVisaDetail::where('candidate_id',$record->id)->get();
                            if (count($candidate_visa)>0) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    )
                    ->schema([
                        Infolists\Components\TextEntry::make('candidate_visa_details.visa_type')->label('Visa Type')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_visa_details.visa_number')->label('Visa Number')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_visa_details.country_of_origin')->label('Country Of Origin')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_visa_details.passport_number')->label('Passport Number')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_visa_details.expiration_date')->label('Expiration Date')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                        Infolists\Components\TextEntry::make('candidate_visa_details.nationalid_number')->label('National Id')->visible(function ($state) {
                            if (!is_null($state[0])) {
                                return true;
                            }
                        }),
                    ])->columns(2),
                //visa validation section end


            ]);
    }
    public function form(Form $form): Form
    {
        return $form
            // ->record(CandidateNote::)
            ->schema([
                ComponentsSection::make('Notes')

                    ->schema([
                        //  TextInput::make('candiate_id')->default(1),
                        Textarea::make('notes')->label('Add Your Notes')->required(),
                    ]),
            ])->statePath('data');
    }


    // below this code Mount function block
    public $candidate_notes = [];
    public ?array $data = [];
    public $candiate_notes_created;
    public $currentDate;
    public $resume_path;
    public $visa_path;
    public $value;
    public $content;

    public $visa;
    public $resume_check;

    public function mount()
    {
        ////send email section start
        $this->offer_template = OfferLetterTemplate::all();
        // dd($this->offer_template);
        ////send email end
        // dd(Candidate::all());
        $this->currentDate = new DateTime();
        // $this->value = 0;
        $this->details = unserialize($_GET['record']);
        $this->com = Candidate::find($this->details);

        $candidate_id = $this->com->id;
        // dd( $candidate_id);
        $this->candidate_notes = CandidateNote::where('candidate_id', $candidate_id)->with('user','user.employee')->get();
        //if resume exists then get resume
        $this->resume_check=CandidateAdditional::where('candidate_id', $candidate_id)->get();
        if (count($this->resume_check)>0){
            $resume = CandidateAdditional::where('candidate_id', $candidate_id)->select('resume_path')->get();

            if (!is_null($resume[0]->resume_path)) {
                $this->value = 1;
                $this->resume_path = $resume[0]->resume_path;

            } else {
               $this->value = 2;
                $this->resume_path = 'empty';
            };
        } else {

            $this->value = 2;
            $this->resume_path = 'empty';
        }
        //resume get end

        // if candiate visa details exists then get record start
        $this->visa=CandidateVisaDetail::where('candidate_id', $candidate_id)->get();
        // dd($this->visa);
        if (count($this->visa)>0) {
            $visa = CandidateVisaDetail::where('candidate_id', $candidate_id)->select('visa_path')->get();
            // dd($visa);
            if ($visa[0]->visa_path!=null && !empty($visa)) {
                // dd($visa);
                $this->visa_path = $visa[0]->visa_path;
            } else {
                $this->visa_path = 'empty';

            };
        } else {
            $this->visa_path = 'empty';
        }
        // if candiate visa details exists then get record end


    }
    // code Mount function block end

    public function Resume($id)
    {
        $this->value = $id;
    }
    // public $notes_value;
    public function Notes($id)
    {
        $this->value = $id;
    }
    public function Visa($id)
    {
        $this->value = $id;
    }


    public function create(): void
    {

        $candidate_id = $this->com->id;
        $content = $this->form->getState('notes');
        $post_content = $content['notes'];
        CandidateNote::create([
            'candidate_id' => $candidate_id,
            'notes' => $post_content,
            'create_by' => auth()->user()->id,

        ]);
        $this->form->fill();
        $candidate_id = $this->com->id;
        $this->candidate_notes = CandidateNote::where('candidate_id', $candidate_id)->with('user','user.employee')->get();
        // dd($this->candidate_notes);
    }


    ///send email section variables and functions
    public $offer_template;
    public $template;
    public $templates;
    public $sms;
    public $message;
    public $detail;
    public $pdf_template;
    // public $contents;
    // public function updated(){
    //     dd($this->image);
    // }
    public function updated($property){
        if($property=='template'){
            $this->emailTemplate();
        }
    }
     public function emailTemplate(){

        // $this->Jobs = Job::where('job_status', 'approved')->get();
        $this->templates=OfferLetterTemplate::where('id',$this->template)->first();
        $this->detail=$this->templates->description;
        // dd($this->templates->description);
        $this->message=$this->templates->content;
        // dd($this->b);
        if($this->templates){

            $this->sms = $this->templates->content;
            $this->content = $this->templates->content;

        }

 }

    public function createTemplate(){

    // $candidate= Candidate::find($this->details);
    $candidate= Candidate::with('job')->where('id',$this->details)->get();
    // $a=OfferLetterTemplate::where('id',$this->template->id)->get();
    // $b=$a[0]->content;
    // $offer=OfferLetterTemplate::where('id',2)->get();
    // dd($offer);
    // dd($candidate[0]->email);

    $data["email"] = $candidate[0]->email;
    $data["name"] = $candidate[0]->name;
    $data["description"] = $this->detail;
    $firstname = $candidate[0]->first_name;
    $lastname  = $candidate[0]->last_name;
    $job=$candidate[0]->job->title;
    $msg=$this->message;
    // $contents=$offer[0]->content;
    // $job= $candidate->job;
    // $folder=File::makeDirectory('/folder',$candidate[0]->id);
    // dd($folder);
    $pdf = PDF::loadView('email-template', compact('firstname','lastname','job','msg'))->save(strval($candidate[0]->id).'storage.pdf');

    if($this->template){
    //   dd('kkk');
    $data["description"] = OfferLetterTemplate::find($this->template)->description ;
    $data["id"]=$candidate[0]->id;
    $data["body"]=OfferLetterTemplate::find($this->template)->name;

    Mail::send('offer-letter', $data, function ($message) use ($data) {
        $message->to($data["email"])
            ->subject($data["body"]);
    });

}
            Notification::make()
            ->title('Email sent successfully')
            ->success()
            ->send();
            return redirect()->to('/recruitment/candidates');

            }


}
