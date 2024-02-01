<?php

namespace App\Livewire;

use App\Models\Employee\EmployeeType;
use App\Models\Recruitment\Candidate;
use App\Models\Recruitment\CandidateAdditional;
use App\Models\Recruitment\CandidateAddress;
use App\Models\Recruitment\CandidateEducation;
use App\Models\Recruitment\CandidateVisaDetail;
use App\Models\Recruitment\Job;
use App\Models\Recruitment\JobAdditional;
use App\Models\Recruitment\JobJobAdditional;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Livewire\Component;
use PragmaRX\Countries\Package\Countries;

class JobApplication extends Component
{

    // General Values
    public $companyId;
    // #[Rule( as: 'date of birth')]
    public $fname;

    public $lname;

    public $email;

    public $number;

    public $salary;

    public $letter;

    public $highEducation;

    public $linkedUrl;

    public $reffredBy;

    public $website;

    public $refrences;

    use WithFileUploads;
    // #[Rule('required|max:1024')]
    public $resume;

    #[Rule('required|image|max:1024')]
    public $photo;

    // Address Values

    public $country;

    public $Selectedcountry;

    public $state;

    public $city;

    public $postalCode;

    public $houseNo;

    public $street1;

    public $street2;

    // Education Values

    public $collegeName;

    public $branch;

    public $batch;

    public $grade;

    // VisaValidation Values

    public $visaType;

    public $visaNumber;

    public $expiry;

    public $passportNumber;

    public $nationalNumber;

    public $visaImg;

    public $Countries;

    public $States = [];

    public $Cities;

    public function submit()
    {
        $validated = $this->validate(
            [
                'fname' => 'required|min:3',
                'lname' => 'required|min:3',
                'email' => 'required|min:3',
                'number' => 'required|min:3',
                'photo' => 'required|max:1024|mimes:png,jpg,svg,jepg,pdf',
                // 'resume' => in_array("Resume", $this->random) ? 'required|max:1024' : '',
                'resume' => (array_search('Resume', array_column($this->random, 1)) !== false  && $this->random[array_search('Resume', array_column($this->random, 1))][0] == 1) ? 'required|max:5120|mimes:pdf' : '',
                // 'salary' => in_array("Desired Salary", $this->jobAdditional) ? 'required|min:3' : '',
                'salary' => (array_search('Desired Salary', array_column($this->random, 1))  !== false  && $this->random[array_search('Desired Salary', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'letter' =>  in_array("Cover Letter", $this->jobAdditional) ? 'required|min:3' : '',
                'letter' => (array_search('Cover Letter', array_column($this->random, 1))  !== false  && $this->random[array_search('Cover Letter', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'linkedUrl' => in_array("LinkedIn URL", $this->jobAdditional) ? 'required|min:3' : '',
                'linkedUrl' => (array_search('LinkedIn URL', array_column($this->random, 1))  !== false  && $this->random[array_search('LinkedIn URL', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'reffredBy' => in_array("Referred By (Employee ID)", $this->jobAdditional) ? 'required' : '',
                'reffredBy' => (array_search('Referred By (Employee ID)', array_column($this->random, 1))  !== false  && $this->random[array_search('Referred By (Employee ID)', array_column($this->random, 1))][0] == 1) ? 'required' : '',
                // 'website' => in_array("Website, Blog or Portfolio", $this->jobAdditional) ? 'required' : '',
                'website' => (array_search('Website, Blog or Portfolio', array_column($this->random, 1))  !== false  && $this->random[array_search('Website, Blog or Portfolio', array_column($this->random, 1))][0] == 1) ? 'required' : '',
                // 'refrences' => in_array("References", $this->jobAdditional) ? 'required|min:3' : '',
                'refrences' => (array_search('References', array_column($this->random, 1))  !== false  && $this->random[array_search('References', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'country' => in_array("Address", $this->jobAdditional) ? 'required' : '',
                'country' => (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? 'required' : '',
                // 'state' => in_array("Address", $this->jobAdditional) ? 'required' : '',
                'state' => (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? 'required' : '',
                // 'city' => in_array("Address", $this->jobAdditional) ? 'required' : '',
                'city' => (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? 'required' : '',
                // 'postalCode' => in_array("Address", $this->jobAdditional) ? 'required|min:3' : '',
                'postalCode' => (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'houseNo' => in_array("Address", $this->jobAdditional) ? 'required|min:3' : '',
                'houseNo' => (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'street1' => in_array("Address", $this->jobAdditional) ? 'required|min:3' : '',
                'street1' => (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'street2' => in_array("Address", $this->jobAdditional) ? 'required|min:3' : '',
                'street2' => (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'collegeName' => in_array("Education Details", $this->jobAdditional) ? 'required|min:3' : '',
                'collegeName' => (array_search('Education Details', array_column($this->random, 1))  !== false  && $this->random[array_search('Education Details', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'highEducation' => in_array("Education Details", $this->jobAdditional) ? 'required|min:3' : '',
                'highEducation' => (array_search('Education Details', array_column($this->random, 1))  !== false  && $this->random[array_search('Education Details', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'branch' => in_array("Education Details", $this->jobAdditional) ? 'required|min:3' : '',
                'branch' => (array_search('Education Details', array_column($this->random, 1))  !== false  && $this->random[array_search('Education Details', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'batch' => in_array("Education Details", $this->jobAdditional) ? 'required|max:4' : '',
                'batch' => (array_search('Education Details', array_column($this->random, 1))  !== false  && $this->random[array_search('Education Details', array_column($this->random, 1))][0] == 1) ? 'required|max:4' : 'max:4',
                // 'grade' => in_array("Education Details", $this->jobAdditional) ? 'required' : '',
                'grade' => (array_search('Education Details', array_column($this->random, 1))  !== false  && $this->random[array_search('Education Details', array_column($this->random, 1))][0] == 1) ? 'required|max:1024' : '',
                // 'visaType' => in_array("Visa Validation", $this->jobAdditional) ? 'required|min:3' : '',
                'visaType' => (array_search('Visa Validation', array_column($this->random, 1))  !== false  && $this->random[array_search('Visa Validation', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'visaNumber' => in_array("Visa Validation", $this->jobAdditional) ? 'required|min:3' : '',
                'visaNumber' => (array_search('Visa Validation', array_column($this->random, 1))  !== false  && $this->random[array_search('Visa Validation', array_column($this->random, 1))][0] == 1) ? 'required|max:12' : '',
                // 'expiry' => in_array("Visa Validation", $this->jobAdditional) ? 'required|max:4' : '',
                'expiry' => (array_search('Visa Validation', array_column($this->random, 1))  !== false  && $this->random[array_search('Visa Validation', array_column($this->random, 1))][0] == 1) ? 'required' : '',
                // 'passportNumber' => in_array("Visa Validation", $this->jobAdditional) ? 'required|max:12' : '',
                'passportNumber' => (array_search('Visa Validation', array_column($this->random, 1))  !== false  && $this->random[array_search('Visa Validation', array_column($this->random, 1))][0] == 1) ? 'required' : '',
                // 'nationalNumber' => in_array("Visa Validation", $this->jobAdditional) ? 'required|min:3' : '',
                'nationalNumber' => (array_search('Visa Validation', array_column($this->random, 1))  !== false  && $this->random[array_search('Visa Validation', array_column($this->random, 1))][0] == 1) ? 'required|min:3' : '',
                // 'visaImg' => in_array("Visa Validation", $this->jobAdditional) ? 'required|max:1024' : '',
                'visaImg' => (array_search('Visa Validation', array_column($this->random, 1))  !== false  && $this->random[array_search('Visa Validation', array_column($this->random, 1))][0] == 1) ?  'required|max:1024|mimes:png,jpg,svg,jepg' : '',
            ],
            [
                'fname.required' => 'The First Name field is required',
                'lname.required' => 'The Last Name field is required',
                'visaImg' => 'The Visa Image field is required',
                'reffredBy' => 'The referred by Name field is required',
                // 'website.required' => 'The website field is required',
                // 'refrences.required' => 'The Refrences field is required',

            ]
        );
        // $this->validate();
        if ($validated) {

            $photo = $this->photo->store('images', 'public');
            $candidate = Candidate::create(
                [
                    'company_id' => $this->companyId,
                    'job_id' => $this->JobID,
                    'status' => 'applied',
                    'first_name' => $this->fname,
                    'last_name' =>  $this->lname,
                    'email' => $this->email,
                    'pnone_number' => $this->number,
                    'photo' => $photo,
                    // 'job_accept_code' => '',
                    // 'rating' => '',
                    // 'offer_sent_by' => '',
                    // 'offer_sent_on' => '',
                    'created_at' => now(),
                    'updated_at' => now(),

                ]
            );

            if ($this->resume || $this->salary || $this->letter || $this->linkedUrl ||  $this->reffredBy || $this->website) {
                $resume = null;
                if ($this->resume) {
                    $resume = $this->resume->store('resume', 'public');
                }
                CandidateAdditional::create([
                    'candidate_id' => $candidate->id,
                    'resume_path' => $resume ? $resume : null,
                    'desired_salary' => $this->salary ? $this->salary : null,
                    'cover_letter' => $this->letter ? $this->letter : null,
                    'linkedin_url' => $this->linkedUrl ? $this->linkedUrl : null,
                    'referredby' => $this->reffredBy ? $this->reffredBy : null,
                    'reference' => $this->refrences ? $this->refrences : null,
                    'website_blog_portfolio' => $this->website ? $this->website : null,
                ]);
            }
            if ($this->country) {
                // dd($this->Selectedcountry, $this->country, $this->state);

                CandidateAddress::create([
                    'candidate_id' => $candidate->id,
                    'country' => $this->Selectedcountry ? $this->Selectedcountry : null,
                    'province' => $this->state ? $this->state : null,
                    'city' => $this->city ? $this->city : null,
                    'postal_code' => $this->postalCode ? $this->postalCode : null,
                    'flat_no' => $this->houseNo ? $this->houseNo : null,
                    'street_1' => $this->street1 ? $this->street1 : null,
                    'street_2' => $this->street2 ? $this->street2 : null,
                ]);
            }

            if ($this->collegeName || $this->highEducation || $this->branch || $this->batch || $this->grade) {
                CandidateEducation::create([
                    'candidate_id' => $candidate->id,
                    'college_name' => $this->collegeName ? $this->collegeName : null,
                    'highest_education' => $this->highEducation ? $this->highEducation : null,
                    'branch' => $this->branch ? $this->branch : null,
                    'passed' => $this->batch ? $this->batch : null,
                    'grade' => $this->grade ? $this->grade : null,
                ]);
            }
            if ($this->visaType || $this->visaNumber || $this->expiry || $this->passportNumber || $this->nationalNumber || $this->visaImg) {
                $visapath = null;
                if ($this->visaImg) {
                    $visapath = $this->visaImg->store('visa', 'public');
                }
                CandidateVisaDetail::create([
                    'candidate_id' => $candidate->id,
                    'visa_type' => $this->visaType ? $this->visaType : null,
                    'visa_number' => $this->visaNumber ? $this->visaNumber : null,
                    'expiration_date' => $this->expiry ? $this->expiry : null,
                    'passport_number' => $this->passportNumber ? $this->passportNumber : null,
                    'nationalid_number' => $this->nationalNumber ? $this->nationalNumber : null,
                    'visa_path' => $visapath ? $visapath : null,

                ]);
            }

            $recipients = User::whereHas('roles', function ($q) {
                $q->where('name', 'HR');
            })->get();
            if ($recipients) {
                foreach ($recipients as $recipient) {
                    Notification::make()
                        ->title('New Candidate Applied')
                        ->actions([
                            Action::make('view')
                                ->button()->url('/recruitment/candidates/detail?record=i%3A' . $candidate->id . '%3B')
                                ->close()
                        ])
                        ->sendToDatabase($recipient);
                }
            }
            return redirect()->to('/open-positions')->with('status', 'Post successfully updated.');
        }
    }

    //     return $this->redirect('/posts')
    //         ->with('status', 'Post successfully created.');
    // }

    public function removeImage()
    {
        $this->photo = '';
    }
    public $JobID;
    public $Job;
    public $emptyp;
    public $jobAddons;
    public $jobAdditional;
    public $random = [];
    public $case1;
    public $case2;
    public $case3;
    public $case4;
    public $case5;
    public $case6;
    public $case7;
    public $case8;
    public $case9;
    public $case10;

    public function mount($id)
    {
        $this->JobID = $id;
        $this->companyId = Job::where('id', $id)->value('company_id');

        $this->Countries = Countries::all()->pluck('name.common', 'cca2')->toArray();
        // dd($this->country);

        $getJob = Job::where('id', $id)->get();
        $this->Job = $getJob[0];
        $getemptyp = $getJob[0]->employee_type_id;

        $getEmpName = EmployeeType::where('id', $getemptyp)->pluck('name');
        $this->emptyp = $getEmpName[0];
        $this->jobAddons = JobJobAdditional::where('job_id', $id)->with('jobAdditional')->orderBy('job_additional_id', 'asc')->get();
        // $this->jobAdditional = JobAdditional::whereHas('jobJobAdditional', function ($query) use ($id) {
        //     $query->where('job_id', $id);
        // })->pluck('name')->toArray();
        $this->jobAdditional = JobJobAdditional::where('job_id', $id)->with('jobAdditional')->orderBy('job_additional_id', 'asc')->get();
        // dd($this->jobAdditional[0]->required,$this->jobAdditional[0]->jobAdditional->name);
        foreach ($this->jobAdditional as $jobAdditionals) {
            array_push($this->random, [$jobAdditionals->required, $jobAdditionals->jobAdditional->name]);
        }
        // dd($this->random);
        //star validation
        $this->case1 = (array_search('Desired Salary', array_column($this->random, 1))  !== false  && $this->random[array_search('Desired Salary', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case2 = (array_search('Cover Letter', array_column($this->random, 1)) !== false  && $this->random[array_search('Cover Letter', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case3 = (array_search('LinkedIn URL', array_column($this->random, 1))  !== false  && $this->random[array_search('LinkedIn URL', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case4 = (array_search('Referred By (Employee ID)', array_column($this->random, 1))  !== false  && $this->random[array_search('Referred By (Employee ID)', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case5 = (array_search('Website, Blog or Portfolio', array_column($this->random, 1))  !== false  && $this->random[array_search('Website, Blog or Portfolio', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case6 = (array_search('References', array_column($this->random, 1))  !== false  && $this->random[array_search('References', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case7 = (array_search('Resume', array_column($this->random, 1)) !== false  && $this->random[array_search('Resume', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case8 = (array_search('Address', array_column($this->random, 1))  !== false  && $this->random[array_search('Address', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case9 = (array_search('Education Details', array_column($this->random, 1))  !== false  && $this->random[array_search('Education Details', array_column($this->random, 1))][0] == 1) ? true : false;
        $this->case10 = (array_search('Visa Validation', array_column($this->random, 1))  !== false  && $this->random[array_search('Visa Validation', array_column($this->random, 1))][0] == 1) ? true : false;
    }


    public function getStates()
    {
        if ($this->country) {
            $Countries = Countries::where('cca2', $this->country)->first();
            $this->States = $Countries->hydrateStates()->states->pluck('name', 'postal')->toArray();
            $this->Selectedcountry = $this->Countries[$this->country];
            // dd($this->Selectedcountry);
        } else {
            $this->States = [];
        }
    }

    public function getCities()
    {
        // if ($this->States) {
        //     $this->dummy = $this->States->hydrateCities()->cities->pluck('name', 'id')->toArray();

        //     dd($this->dummy);
        //     $this->Cities = $this->dummy->hydrateCities()->cities->pluck('name', 'id')->toArray();
        // }
    }

    public function render()
    {
        $this->jobAddons = JobJobAdditional::where('job_id', $this->JobID)->with('jobAdditional')->orderBy('job_additional_id', 'asc')->get();
        return view('livewire.job-application');
    }
}
