<?php

namespace App\Filament\Resources\Employee\EmployeeResource\Pages;

use App\Filament\Resources\Employee\EmployeeResource;
use App\Imports\EmployeeImport;
use App\Models\Company\Company;
use App\Models\Employee\BankInfo;
use App\Models\Employee\Contact;
use App\Models\Employee\CurrentAddress;
use App\Models\Employee\Employee;
use App\Models\Employee\Gender;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Filament\Notifications\Auth\ResetPassword;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Mail;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\Request;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class ImportEmployee extends Page
{
    protected static string $resource = EmployeeResource::class;

    protected static string $view = 'filament.resources.employee.employee-resource.pages.import-employee';
    public $collection = [];
    public $options;
    #[Rule('required')]
    public $companyId;

    public $companies;
    public $columnList = [];
    public $remainOptions = [];
    public $employeeData = [];
    public $requiredOptions = [];
    public $selectedRequiredOptions = [];
    public $skipEmployee = [];

    public function mount()
    {

        $this->options = [
            'name' => ['First Name', true],
            'last_name' => ['Last Name', true],
            'email' => ['Email', true],
            'date_of_birth' => ['Date Of Birth', false],
            'mobile_phone' => ['Mobile Phone', false],
            'work_phone' => ['Work Phone', false],
            'home_phone' => ['Phone Home', false],
            'country' => ['Country', false],
            'street' => ['Street', false],
            'city' => ['City', false],
            'state' => ['State', false],
            'zip' => ['Zip', false],
            'gender_id' => ['Gender', false],
            'bank_name' => [' Bank Name', false],
            'ifsc' => ['IFSC', false],
            'micr' => ['MICR', false],
            'account_number' => ['Account Number', false],
            'branch_code' => ['Branch Code', false],



        ];
        $this->companies = Company::all();
        foreach ($this->options as $key => $option) {
            if ($option[1]) {
                array_push($this->requiredOptions, $key);
            }
        }
        $this->selectedRequiredOptions = array_intersect_assoc($this->columnList, $this->requiredOptions);
    }
    public function updated($property, $value)
    {
        // unset($this->options[$value]);
        $this->selectedRequiredOptions = array_intersect($this->columnList, $this->requiredOptions);
        sort($this->selectedRequiredOptions);
        sort($this->requiredOptions);
    }
    public function save()
    {
        $this->validate();
        $this->employeeData = [];

        foreach ($this->collection as $key => $row) {
            if ($key != 0) {
                $employeeData = [];
                foreach ($this->columnList as $column => $field) {
                    if ($field) {
                        // dd($column,$row,$row[$column]);
                        $employeeData[$field] = $row[$column];
                    }
                }
                $employeeData['password'] = 'password';
                array_push($this->employeeData, $employeeData);
            }
        }
        Notification::make()
            ->title('Imported successfully')
            ->success()
            ->send();
    }

    public function rendered()
    {
        if ($this->employeeData) {
            $this->js(" setTimeout(function(){
                document.getElementById('importData').scrollIntoView()
            }, 1000)");
        }
    }
    public function create()
    {
        if ($this->employeeData) {


            Notification::make()
                ->title('Please Wait until its completed')
                ->success()
                ->send();
            try {

                foreach ($this->employeeData as $key => $data) {

                    if (array_key_exists($key, $this->skipEmployee) ? !$this->skipEmployee[$key] : true) {
                        if (array_key_exists('date_of_birth', $data)) {
                            $data['date_of_birth'] = Carbon::create($data['date_of_birth']);
                        }
                        if (array_key_exists('gender_id', $data)) {
                            $gender = Gender::where('name', 'LIKE', '%' . $data['gender_id'] . '%')->first();
                            if (!is_null($gender)) {

                                $data['gender_id'] = $gender->id;
                            } else {
                                unset($data['gender_id']);
                            }
                        }
                        if (count($data) > 1) {
                            // create user
                            $user = User::create($data);


                            $data['user_id'] = $user->id;
                            if (count($data) > 1) {
                                // personal details
                                if ($this->companyId || array_key_exists('date_of_birth', $data) || array_key_exists('mobile_phone', $data)) {
                                    Employee::create(array_merge(['company_id' => $this->companyId],  $data));
                                    // sending password reset link
                                    $company = Company::whereHas('employee', function ($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                    })->first();
                                    $data["hr"] = '';

                                    if ($company) {
                                        $datas["company"] = $company->name;
                                    }
                                    $hr = User::whereHas('roles', function ($query) {
                                        $query->where('name', 'HR');
                                    })->first();
                                    if ($hr) {
                                        $datas["hr"] = $hr;
                                    }
                                    // generate password reset link
                                    $token = app('auth.password.broker')->createToken($user);
                                    $notification = new ResetPassword($token);
                                    $notification->url = Filament::getResetPasswordUrl($token, $user);
                                    $datas["email"] = $user->email;
                                    $datas["title"] = " Welcome to " . $company->name . '!';
                                    $datas["name"] =  $user->last_name ? $user->name . ' ' . $user->last_name : $user->name;
                                    $datas["url"] =  $notification->url;
                                    Mail::send('loginTemplate', $datas, function ($message) use ($datas) {
                                        $message->to($datas["email"])

                                            ->subject($datas["title"]);
                                    });
                                }
                                // address details
                                if (array_key_exists('country', $data) || array_key_exists('city', $data) || array_key_exists('state', $data) || array_key_exists('zip', $data)) {
                                    $address = new CurrentAddress(array_merge(['company_id' => $this->companyId], $data));
                                    $user->currentAddress()->save($address);
                                }
                                // bank info
                                if (array_key_exists('bank_name', $data) || array_key_exists('ifsc', $data) || array_key_exists('micr', $data) || array_key_exists('account_number', $data) || array_key_exists('branch_code', $data)) {
                                    BankInfo::create($data);
                                }

                                // contact info
                                if (array_key_exists('work_phone', $data) || array_key_exists('mobile_phone', $data) || array_key_exists('home_phone', $data) || array_key_exists('home_email', $data)) {
                                    Contact::create($data);
                                }
                            }
                        }
                    }
                }
                Notification::make()
                    ->title('Created successfully')
                    ->success()
                    ->send();
            }
            // Should be $this->buildXMLHeader();
            catch (Exception $e) {
                Notification::make()
                    ->title('importing failed')
                    ->body('please upload correct data')
                    ->danger()
                    ->send();
                return $e;
            }
        }
    }


    public function uploadChunk(Request $request)
    {

        // create the file receiver
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {

            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
            return $this->saveFile($save->getFile());
        }

        // we are in chunk mode, lets send the current progress
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }


    /**
     * create Filename
     *
     * @param  mixed $file
     * @return string  filename
     */
    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace("." . $extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }
    protected function saveFile(UploadedFile $file)
    {

        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());
        $type = explode("-", $mime);
        $fileName = $this->createFilename($file) . 'xlsx';
        // Group files by the date (week
        $dateFolder = date("Y-m-W");

        // Build the file path
        $filePath = "upload/{$mime}/{$dateFolder}";
        $finalPath = "storage/" . $filePath;

        // move the file name
        $file->move($finalPath, $fileName);

        return response()->json([
            'path' => $filePath,
            'name' => $fileName,
            'mime_type' => $mime
        ]);
    }
    #[On('setExcelPath')]
    public function setExcelPath($videoPath)
    {
        $path = Storage::path('public/' . $videoPath);
        try {

            $collection = (new EmployeeImport)->toCollection($path);
            if ($collection) {
                $this->collection = $collection->first();
            }
        } catch (Exception $e) {
        }

        // return Excel::import(new EmployeeImport,$path);

    }
}
