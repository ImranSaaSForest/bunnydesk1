<?php

namespace App\Filament\Resources\MyInfoResource\Pages;

use App\Filament\Resources\MyInfoResource;
use App\Models\Company\Company;
use App\Models\Company\Designation;
use App\Models\Employee\BankInfo;
use App\Models\Employee\Contact;
use App\Models\Employee\CurrentAddress;
use App\Models\Employee\Education;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeStatus;
use App\Models\Employee\EmployeeType;
use App\Models\Employee\Employment;
use App\Models\Employee\Gender;
use App\Models\Employee\JobInfo;
use App\Models\Employee\MaritalStatus;
use App\Models\Employee\PaymentInterval;
use App\Models\Employee\PaymentMethod;
use App\Models\Employee\SalaryDetail;
use App\Models\Employee\Team;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Support\Htmlable;

class MyInfo extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public ?array $job = [];
    public ?array $userdata = [];
    public ?array $employee = [];
    public ?array $education = [];
    public ?array $onboardingList = [];
    public ?array $offboardingList = [];
    public ?array $bank = [];
    public ?array $compensation = [];
    public ?array $jobinfo = [];
    public ?array $role = [];
    public ?array $address = [];



    protected static string $resource = MyInfoResource::class;

    protected static string $view = 'filament.resources.my-info-resource.pages.my-info';

    protected static ?string $title = 'My Info';

    // protected static ?string $navigationLabel = 'Custom Navigation Label';

    // public static function getNavigationLabel(): string
    // {
    // return __('dfdsfs');
    //  }

    // public function userform(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             TextInput::make('name')->label('First Name'),
    //             TextInput::make('last_name')->label('Last Name'),
    //             TextInput::make('email')
    //             ->label('Email')
    //             ->required(),
    //         ])
    //         ->columns(2)
    //         ->statePath('userdata')
    //         ->model(User::class);
    // }


    public function form(Form $form): Form
    {
        return $form
            // 'company_id',
            // 'date_of_birth',
            // 'gender_id',
            // 'marital_status_id',
            // 'social_security_number',
            ->schema([
                TextInput::make('name')
                    ->label('Name'),
                TextInput::make('last_name')
                    ->label('Last Name'),
                TextInput::make('email')
                    ->label('Email'),
                TextInput::make('date_of_birth')
                    ->label('Date of birth'),
                TextInput::make('social_security_number')
                    ->label('Mobile Number'),
                Select::make('company_id')->label('Company')
                    ->options(Company::all()->pluck('name', 'id')),
                Select::make('marital_status_id')->label('Marital Status')
                    ->options(MaritalStatus::all()->pluck('name', 'id'))
                    ->required(),
                Select::make('gender_id')->label('Gender')
                    ->options(Gender::pluck('name', 'id'))
                    ->required(),
                // SpatieMediaLibraryFileUpload::make('media')
                // ->collection('employee-images')
                // ->label('Profile Picture')->columnSpanFull(),
                TextInput::make('state')
                    ->label('State'),
                TextInput::make('street')
                    ->label('Street'),
                TextInput::make('city')
                    ->label('City'),
                TextInput::make('zip')
                    ->label('Pin code')

            ])->disabled(function () {
                if ($this->edit_val == 1) {
                    return false;
                } else {
                    return true;
                }
            })
            ->columns(2)
            ->statePath('data')->model(Employee::class);;
    }

    public function jobForm(Form $form): Form
    {
        return $form
            // 'designation_id',
            // 'report_to',
            // 'team_id',
            // 'shift_id',
            ->schema([
                Select::make('designation_id')->label('Designation')
                    ->options(Designation::pluck('name', 'id')),
                Select::make('report_to')->label('Report To')
                    ->options(User::pluck('name', 'id')),
                Select::make('team_id')
                    ->label('Team')
                    ->options(Team::pluck('name', 'id')),
                Select::make('shift_id')
                    ->label('Shift'),

            ])->disabled(function () {
                if ($this->edit_val == 2) {
                    return false;
                } else {
                    return true;
                }
            })
            ->columns(2)
            ->statePath('jobinfo')
            ->model(JobInfo::class);
    }

    //roles form
    public function roles(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('roles')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable(function () {
                        if ($this->edit_val == 2) {
                            return false;
                        } else {
                            return true;
                        }
                    }),
            ])->disabled(function () {
                if ($this->edit_val == 2) {
                    return false;
                } else {
                    return true;
                }
            })
            ->statePath('role');
    }

    public function employmentForm(Form $form): Form
    {
        // 'employment_id',
        // 'hired_on',
        // 'employee_type_id',
        // 'effective_date',
        // 'employee_status_id',
        return $form
            ->schema([
                TextInput::make('employment_id')
                    ->label('Employee ID'),
                Select::make('employee_type_id')->label('Employee Type')
                    ->options(EmployeeType::pluck('name', 'id')),
                Select::make('employee_status_id')->label('Employee Status')
                    ->options(EmployeeStatus::pluck('name', 'id')),
                DatePicker::make('effective_date'),
                DatePicker::make('hired_on'),


            ])->disabled(function () {
                if ($this->edit_val == 2) {
                    return false;
                } else {
                    return true;
                }
            })
            ->columns(2)
            ->statePath('employee')->model(Employment::class);;
    }

    public function educationForm(Form $form): Form
    {
        // 'school_college',
        // 'degree',
        // 'course',
        // 'grade',
        // 'course_from',
        // 'course_to',
        // 'description',
        return $form
            ->schema([
                TextInput::make('school_college')->label("College"),
                TextInput::make('degree')
                    ->label('Higher Education'),
                TextInput::make('course')
                    ->label('Course'),
                // TextInput::make('')
                //     ->label('Batch'),
                TextInput::make('grade')
                    ->label('Grade')
            ])->disabled(function () {
                if ($this->edit_val == 6) {
                    return false;
                } else {
                    return true;
                }
            })
            ->columns(2)
            ->statePath('education')->model(Education::class);;
    }


    public function bankForm(Form $form): Form
    {
        // 'bank_name',
        // 'name',
        // 'ifsc',
        // 'micr',
        // 'account_number',
        // 'branch_code',
        return $form
            ->schema([
                TextInput::make('bank_name')
                    ->label('Bank name'),
                TextInput::make('ifsc')
                    ->label('Ifsc'),
                TextInput::make('micr')
                    ->label('Micr'),
                TextInput::make('account_number')
                    ->label('Account number'),
                TextInput::make('branch_code')
                    ->label('Branch code'),

            ])->disabled(function () {
                if ($this->edit_val == 5) {
                    return false;
                } else {
                    return true;
                }
            })
            ->columns(2)
            ->statePath('bank');
    }

    public function compensationForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('payment_interval_id')
                    ->label('Payment Interval')
                    ->options(PaymentInterval::pluck('name', 'id')),
                Select::make('payment_method_id')
                    ->label('Payment Method')
                    ->options(PaymentMethod::pluck('name', 'id')),
                TextInput::make('amount'),
                TextInput::make('currency'),
            ])->disabled(function () {
                if ($this->edit_val == 4) {
                    return false;
                } else {
                    return true;
                }
            })
            ->columns(2)
            ->statePath('compensation');
    }

    public function onboardingListForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('')
                    ->label('Select Onboard List'),
            ])
            ->statePath('onboardingList');
    }

    public function offboardingListForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('')
                    ->label('Select Offboard List'),
            ])
            ->statePath('offboardingList');
    }

    protected function getForms(): array
    {
        return [
            // 'userform',
            'form',
            'jobForm',
            'roles',
            'employmentForm',
            'educationForm',
            'bankForm',
            'onboardingListForm',
            'offboardingListForm',
            'compensationForm',
        ];
    }

    public $auth_user;

    public $user_data;
    public $address_data;
    public $contact_data;
    public $compensation_data;
    public $bank_data;
    public $education_data;
    public $employement_data;
    public $employee_job_info;
    public $user_role;

    public $getRecord;
    public $image;

    public $user_id;

    public function getTitle(): string | Htmlable
    {
        $user = User::find($this->user_id);
        if(auth()->user()->id==$this->user_id){
           return __('My Info');
        }else{
            return __($user->name);
        }

    }

    public function mount($record): void
    {

        $user = User::find($record);
        $this->user_id=$user->id;
        // if($this->image_update==false){
        // if(auth()->user()->name==$user->name){
        //     self::$title = "My Info";
        // }else{
        //     self::$title = $user->name;
        // }
        // }

        $this->getRecord = $user;
        $this->auth_user = $user->id;
        // dd( $this->auth_user);

        //models
        $employee = Employee::where('user_id', $this->auth_user)->first();
        // $contact_model=Contact::where('user_id', $this->auth_user)->get();
        $address_model = User::where('id', $this->auth_user)->with('currentAddress')->get();
        $compensation_model = SalaryDetail::where('user_id', $this->auth_user)->get();
        $bank_model = BankInfo::where('user_id', $this->auth_user)->get();
        $Job_info = JobInfo::where('user_id', $this->auth_user)->get();
        $employment_model = Employment::where('user_id', $this->auth_user)->get();
        $education_model = Education::where('user_id', $this->auth_user)->get();
        // $address = CurrentAddress::where('user_id', $this->auth_user)->get();


        //

        if (count($Job_info) != 0) {
            $this->employee_job_info = [
                'designation_id' => $Job_info[0]->designation_id ?? null,
                'report_to' => $Job_info[0]->report_to ?? null,
                'team_id' => $Job_info[0]->team_id ?? null,
                'shift_id' => $Job_info[0]->shift_id ?? null,

            ];
        }
        if (!is_null($user->roles[0])) {
            $this->user_role = [
                'roles' => $user->roles[0]->name ?? null,
            ];
        }

        //user employee address
        $this->data = [
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];

        if ($employee) {
            $this->data += [
                'company_id' => $employee->company_id ?? null,
                'date_of_birth' => $employee->date_of_birth ?? null,
                'marital_status_id' => $employee->marital_status_id ?? null,
                'social_security_number' => $employee->social_security_number ?? null,
                'gender_id' => $employee->gender_id ?? null,
            ];
        }
        if (!is_null($address_model[0]->currentAddress)) {
            $this->data += [
                'street' => $address_model[0]->currentAddress->street ?? null,
                'city' => $address_model[0]->currentAddress->city ?? null,
                'state' => $address_model[0]->currentAddress->state ?? null,
                'zip' => $address_model[0]->currentAddress->zip ?? null,
            ];
        }
        //user employee address

        if (count($compensation_model) != 0) {
            $this->compensation_data = [
                'amount' => $compensation_model[0]->amount ?? null,
                'currency' => $compensation_model[0]->currency ?? null,
                'payment_interval_id' => $compensation_model[0]->payment_interval_id ?? null,
                'payment_method_id' => $compensation_model[0]->payment_method_id ?? null,
            ];
        }
        if (count($bank_model) != 0) {
            $this->bank_data = [
                'bank_name' => $bank_model[0]->bank_name ?? null,
                'name' => $bank_model[0]->name ?? null,
                'ifsc' => $bank_model[0]->ifsc ?? null,
                'micr' => $bank_model[0]->micr ?? null,
                'account_number' => $bank_model[0]->account_number ?? null,
                'branch_code' => $bank_model[0]->branch_code ?? null,
            ];
        }
        if (count($education_model) != 0) {
            $this->education_data = [
                'school_college' => $education_model[0]->school_college ?? null,
                'degree' => $education_model[0]->degree ?? null,
                'course' => $education_model[0]->course ?? null,
                'grade' => $education_model[0]->grade ?? null,
                // 'course_from' => $education_model[0]->course_from,
            ];
        }
        if (count($employment_model) != 0) {
            $this->employement_data = [
                'employment_id' => $employment_model[0]->employment_id ?? null,
                'hired_on' => $employment_model[0]->hired_on ?? null,
                'employee_type_id' => $employment_model[0]->employee_type_id ?? null,
                'effective_date' => $employment_model[0]->effective_date ?? null,
                'employee_status_id' => $employment_model[0]->employee_status_id ?? null,
            ];
        }
        if (count($bank_model) != 0) {
            $this->bank_data = [
                'bank_name' => $bank_model[0]->bank_name ?? null,
                // 'name' => $bank_model[0]->name,
                'ifsc' => $bank_model[0]->ifsc ?? null,
                'micr' => $bank_model[0]->micr ?? null,
                'account_number' => $bank_model[0]->account_number ?? null,
                'branch_code' => $bank_model[0]->branch_code ?? null,
            ];
        }


        //user and employee
        $this->form->fill($this->data);
        $this->jobForm->fill($this->employee_job_info);
        $this->roles->fill($this->user_role);
        $this->employmentForm->fill($this->employement_data);
        $this->educationForm->fill($this->education_data);
        $this->bankForm->fill($this->bank_data);
        $this->compensationForm->fill($this->compensation_data);
        $this->onboardingListForm->fill();
        $this->offboardingListForm->fill();
        //filling informations
        static::authorizeResourceAccess();
    }

    public function updated()
    {
        if ($this->image) {
            $employee = Employee::where('user_id', $this->auth_user)->first();
            if ($employee) {
                $existingImage = $employee->getMedia('employee-images')->first();
                if ($existingImage) {
                 // If an image already exists, delete it
                    $existingImage->delete();
                }
                // Add the new image
                $employee->addMedia($this->image)->toMediaCollection('employee-images');
                // dd('Image updated successfully');
            }
        }

    }

    public $edit_val='';
    public function Edit($val)
    {
        $this->edit_val = $val;
    }

    public function Cancel(){
        $this->edit_val='';
    }

    public $SelectTabValue=1;
    public function SelectTap($tap_val)
    {
        $this->SelectTabValue = $tap_val;
    }

    public function Create()
    {
        $user_update = User::find($this->auth_user);
        $employee_update = Employee::where('user_id', $this->auth_user)->get();
        // $contact_model=Contact::where('user_id', $this->auth_user)->get();
        $address_model = CurrentAddress::where('addressable_id', $this->auth_user)->get();
        $compensation_model = SalaryDetail::where('user_id', $this->auth_user)->get();
        $bank_model = BankInfo::where('user_id', $this->auth_user)->get();
        $Job_info = JobInfo::where('user_id', $this->auth_user)->get();
        $employment_model = Employment::where('user_id', $this->auth_user)->get();
        $education_model = Education::where('user_id', $this->auth_user)->get();
        // dd(count($employee_update)!=0 && count($address_model)!=0,count($employee_update)==0 && count($address_model)!=0,count($employee_update)==0 && count($address_model)==0,count($employee_update)!=0 && count($address_model)==0);


        if ($this->edit_val == 1) {
            $user_form = $this->form->getState();
            // dd($user_form['name']);
            $user_update->update([
                'name' => $user_form['name'],
                'email' => $user_form['email'],
            ]);
            if (count($employee_update) == 0 && count($address_model) == 0) {
                Employee::create([
                    'user_id' => $this->auth_user,
                    'company_id' => $user_form['company_id'],
                    'date_of_birth' => $user_form['date_of_birth'],
                    'gender_id' =>  $user_form['gender_id'],
                    'marital_status_id' => $user_form['marital_status_id'],
                    'social_security_number' =>  $user_form['social_security_number'],
                ]);
                $user_update->currentAddress()->create([
                    'street' => $user_form['street'],
                    'city' => $user_form['city'],
                    'state' => $user_form['state'],
                    'zip' => $user_form['zip'],
                ]);
            } elseif (count($employee_update) != 0 && count($address_model) == 0) {
                $user_update->currentAddress()->create([
                    'street' => $user_form['street'],
                    'city' => $user_form['city'],
                    'state' => $user_form['state'],
                    'zip' => $user_form['zip'],
                ]);
                Employee::where('user_id', $this->auth_user)->update([
                    'company_id' => $user_form['company_id'],
                    'date_of_birth' => $user_form['date_of_birth'],
                    'gender_id' =>  $user_form['gender_id'],
                    'marital_status_id' => $user_form['marital_status_id'],
                    'social_security_number' => $user_form['social_security_number'],
                ]);
            } elseif (count($employee_update) == 0 && count($address_model) != 0) {

                $user_update->currentAddress()->update([
                    'street' => $user_form['street'],
                    'city' => $user_form['city'],
                    'state' => $user_form['state'],
                    'zip' => $user_form['zip'],
                ]);
                Employee::create([
                    'user_id' => $this->auth_user,
                    'company_id' => $user_form['company_id'],
                    'date_of_birth' => $user_form['date_of_birth'],
                    'gender_id' =>  $user_form['gender_id'],
                    'marital_status_id' => $user_form['marital_status_id'],
                    'social_security_number' =>  $user_form['social_security_number'],

                ]);
            } else {
                Employee::where('user_id', $this->auth_user)->update([
                    'company_id' => $user_form['company_id'],
                    'date_of_birth' => $user_form['date_of_birth'],
                    'gender_id' =>  $user_form['gender_id'],
                    'marital_status_id' => $user_form['marital_status_id'],
                    'social_security_number' => $user_form['social_security_number'],

                ]);
                $user_update->currentAddress()->update([
                    'street' => $user_form['street'],
                    'city' => $user_form['city'],
                    'state' => $user_form['state'],
                    'zip' => $user_form['zip'],
                ]);
            }
        }
        //job info table
        if ($this->edit_val == 2) {
            if (count($Job_info) != 0) {
                JobInfo::where('user_id', $this->auth_user)->update($this->jobForm->getState());
            } else {
                $job = $this->jobForm->getState();
                $job['user_id'] = $this->auth_user;
                JobInfo::create($job);
            }
           // adding role
            if(!is_null($user_update->roles[0])){
                $rolename=Role::find($this->roles->getState()['roles']);
                $user_update->roles()->detach();
                $user_update->assignRole($rolename->name);
            }
        }
        if ($this->edit_val == 2) {
        }
        // employement table
        if ($this->edit_val == 3) {
            if (count($employment_model) != 0) {
                Employment::where('user_id', $this->auth_user)->update($this->employmentForm->getState());
            } else {
                $emp = $this->employmentForm->getState();
                $emp['user_id'] = $this->auth_user;
                Employment::create($emp);
            }
        }
        // compensation table
        if ($this->edit_val == 4) {
            if (count($compensation_model) != 0) {
                SalaryDetail::where('user_id', $this->auth_user)->update($this->compensationForm->getState());
            } else {
                $salary = $this->compensationForm->getState();
                $salary['user_id'] = $this->auth_user;
                SalaryDetail::create($salary);
            }
        }
        // education table
        if ($this->edit_val == 6) {
            if (count($education_model) != 0) {
                Education::where('user_id', $this->auth_user)->update($this->educationForm->getState());
            } else {
                $education = $this->educationForm->getState();
                $education['user_id'] = $this->auth_user;
                Education::create($education);
            }
        }
        if ($this->edit_val == 5) {
            if (count($bank_model) != 0) {
                BankInfo::where('user_id', $this->auth_user)->update($this->bankForm->getState());
            } else {
                $bank = $this->bankForm->getState();
                $bank['user_id'] = $this->auth_user;
                BankInfo::create($bank);
            }
        }

        Notification::make()
        ->title('Saved successfully')
        ->success()
        ->send();

    }
}

        // $this->contact_data=[
        //     'work_phone' => $contact_model[0]->work_phone,
        //     'mobile_phone' => $contact_model[0]->mobile_phone,
        //     'home_phone' => $contact_model[0]->home_phone,
        //     'home_email' => $contact_model[0]->home_email,
        // ];
