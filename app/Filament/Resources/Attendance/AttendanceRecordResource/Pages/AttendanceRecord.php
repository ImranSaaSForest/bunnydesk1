<?php
namespace App\Filament\Resources\Attendance\AttendanceRecordResource\Pages;
use App\Filament\Resources\Attendance\AttendanceRecordResource;
use App\Models\Attendance\AttendanceRecord as AttendanceAttendanceRecord;
use App\Models\Attendance\AttendanceType;
use App\Models\User;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\c;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
class AttendanceRecord extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = AttendanceRecordResource::class;
    protected static ?string $title = 'Attendance';
    protected static string $view = 'filament.resources.attendance.attendance-record-resource.pages.attendance-record';
    public ?array $data = [];
    #[Rule('required|gt:out')]
    public $in;
    #[Rule('required')]
    public $out;
    #[Rule('required')]
    public $attendanceTypeId;
    public $attendanceTypes;
    #[Rule('required')]
    public $status = 'pending';
    public $statuses = [];
    public $reason;
    public $users;
    public ?array $break = [];
    public $paidBreak;
    public $unpadiBreak;
    public function mount(): void
    {

        $generalSetting = app(GeneralSettings::class);
        $this->paidBreak=$generalSetting->paid_break;
        $this->unpadiBreak=$generalSetting->unpaid_break;
        // dd(  $this->unpadiBreak );
        $this->attendanceTypes = AttendanceType::all()->pluck('name', 'id');
        $this->statuses = [
            'Pending' => 'pending',
            'Approved' => 'approved',
            'Rejected' => 'rejected'
        ];
        $this->users = User::whereNotIn('id', [auth()->id()])->pluck('name', 'id');
        // $this->form->fill();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->reactive()
                    ->afterStateUpdated(function (?string $state, ?string $old, Get $get) {
                        //Get the last record
                        $lastRecord = AttendanceAttendanceRecord::where('user_id', $state)->where('attendance_type_id', $this->attendanceTypeId)->orderBy('updated_at', 'desc')->first();
                        if (!is_null($lastRecord)) {
                            if (is_null($lastRecord->out)) {
                                $this->form->fill($lastRecord->toArray());
                            } else {
                                $this->form->fill([
                                    'user_id' => $get('user_id'),
                                    'reason' => $get('reason')
                                ]);
                            }
                        } else {
                            $this->form->fill([
                                'user_id' => $get('user_id'),
                                'reason' => $get('reason')
                            ]);
                        }
                    })
                    ->label('User')
                    ->options(function () {
                        return User::where('id', auth()->id())->pluck('name', 'id');
                    })
                    ->required(),
                Select::make('attendance_type_id')
                    ->options($this->attendanceTypes)
                    ->default($this->attendanceTypeId)
                    ->required()
                    ->hidden()
                    ->disabled()
                    ->dehydrated(),
                // TextInput::make('reason'),
                DateTimePicker::make('in')
                ->label('start')
                    ->seconds(false)
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            if ($get('out')) {
                                $out = Carbon::create($get('out'));
                                if ($value) {
                                    $in = Carbon::create($value);
                                }
                                if (!$in->lt($out)) {
                                    $fail("Please select a date that is less than out.");
                                }
                            }
                        },
                    ])
                    ->disabled(function (Get $get) {
                        $lastRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->where('attendance_type_id', $this->attendanceTypeId)->orderBy('updated_at', 'desc')->first();
                        if (!is_null($lastRecord)) {
                            if (!is_null($lastRecord->in) && is_null($lastRecord->out)) {
                                return true;
                            }
                        }
                    })
                    ->dehydrated()
                    ->required(),
                DateTimePicker::make('out')
                ->label('end')
                    ->seconds(false)
                    ->required(function (Get $get) {
                        $lastRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->where('attendance_type_id', $this->attendanceTypeId)->orderBy('updated_at', 'desc')->first();
                        if (!is_null($lastRecord)) {
                            if (is_null($lastRecord->out)) {
                                return true;
                            }
                        }
                    })
                    ->hidden(function (Get $get) {
                        $lastRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->where('attendance_type_id', $this->attendanceTypeId)->orderBy('updated_at', 'desc')->first();
                        if (!is_null($lastRecord)) {
                            if (!is_null($lastRecord->out)) {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    })
                    // ->dehydrated(function (Get $get) {
                    //     $lastRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->where('attendance_type_id', $this->attendanceTypeId)->orderBy('updated_at', 'desc')->first();
                    //     if (!is_null($lastRecord)) {
                    //         if (!is_null($lastRecord->out)) {
                    //             return true;
                    //         }
                    //     }
                    // })
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            if ($get('in')) {
                                $in = Carbon::create($get('in'));
                                if ($value) {
                                    $out = Carbon::create($value);
                                }
                                if (!$in->lt($out)) {
                                    $fail("Please select a date that is greater than in.");
                                }
                            }
                            $attendanceTypeRecord = AttendanceType::find($this->attendanceTypeId);
                            if ($attendanceTypeRecord->name == 'work') {
                                $lastBreakRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->whereNotIn('attendance_type_id', [$this->attendanceTypeId])->orderBy('updated_at', 'desc')->first();
                                if ($lastBreakRecord) {
                                    if (!$lastBreakRecord->out) {
                                        Notification::make()
                                            ->title("You can't logout without finishing the break.")
                                            ->success()
                                            ->send();
                                        $fail("");
                                    }
                                }
                            }
                        },
                    ])
                    // ...
                    ->disabledOn('edit')
            ])
            ->statePath('data');
    }
    public function breakForm(Form $form): Form
    {
      
       
        return $form
            ->schema([
                Select::make('user_id')
                    ->reactive()
                    ->label('User')
                    // ->options(User::all()->pluck('name', 'id'))
                    ->options(function () {
                        return User::where('id', auth()->id())->pluck('name', 'id');
                    })
                    ->required(),
                // Select::make('attendance_type_id')
                //     ->options($this->attendanceTypes)
                //     ->default($this->attendanceTypeId)
                //     ->required()
                //     ->hidden()
                //     ->disabled()
                //     ->dehydrated(),
                Select::make('attendance_type_id')
                    // ->live(onBlur: true)
                    ->reactive()
                    ->label('breaks')
                    ->afterStateUpdated(function (?string $state, ?string $old, Get $get) {
                        //Get the last record
                        $lastRecord = AttendanceAttendanceRecord::where('user_id', $get('user_id'))->where('attendance_type_id', $state)->orderBy('updated_at', 'desc')->first();
                        // dd($lastRecord);
                        if (!is_null($lastRecord)) {
                            if (is_null($lastRecord->out)) {
                                $this->breakForm->fill($lastRecord->toArray());
                            } else {
                                $this->breakForm->fill([
                                    'user_id' => $get('user_id'),
                                    'attendance_type_id' => $state
                                ]);
                            }
                        } else {
                            $this->breakForm->fill([
                                'user_id' => $get('user_id'),
                                'attendance_type_id' => $state
                            ]);
                        }
                    })
                    ->options(AttendanceType::whereNotIn('name', ['work','unpaid break'])->get()->pluck('name', 'id')),
                DateTimePicker::make('in')->label('Start')
                    ->reactive()
                    ->prefixIcon('heroicon-m-check-circle')
                    ->seconds(false)
                    ->required()
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            if ($get('out')) {
                                $out = Carbon::create($get('out'));
                                if ($value) {
                                    $in = Carbon::create($value);
                                }
                                if (!$in->lt($out)) {
                                    $fail("Please select a date that is less than out.");
                                }
                            }
                        },
                    ])
                    ->disabled(function (Get $get) {
                        $lastRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->where('attendance_type_id', $this->attendanceTypeId)->orderBy('updated_at', 'desc')->first();
                        $checkin = AttendanceAttendanceRecord::where('user_id',  auth()->id())->latest()->first();
                        // dd($checkin);
                        if (!is_null($lastRecord)) {
                            if ($checkin->attendance_type_id != 1) {
                                if (!is_null($lastRecord->in) && is_null($lastRecord->out)) {
                                    return true;
                                }
                            } else {
                                return false;
                            }
                        }
                    })
                    ->dehydrated()
                    ->afterStateUpdated(function (Get $get) {
                        $u = $get('user_id');
                        $a = $get('attendance_type_id');
                        $v = AttendanceAttendanceRecord::where('user_id', $u)->latest()->first();
                        $checkin = AttendanceAttendanceRecord::where('user_id',  auth()->id())->latest()->first();
                        // dd($checkin);
                        if (!is_null($v)) {
                            if ($checkin->attendance_type_id != 1) {
                                if (!is_null($v->in) && is_null($v->out)) {
                                    $attendance_type = AttendanceType::where('id', $v->attendance_type_id)->get();
                                    //   dd($attendance_type);
                                    Notification::make()
                                        ->title("Please Complete your Last Status" . $attendance_type[0]->name)
                                        ->success()
                                        ->send();
                                    return redirect('attendance/attendance-records/create');
                                };
                            }
                        }
                    }),
                // out
                DateTimePicker::make('out')->label('end')
                    ->seconds(false)
                    ->required(function (Get $get) {
                        $lastRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->where('attendance_type_id', $get('attendance_type_id'))->orderBy('updated_at', 'desc')->first();
                        $checkin = AttendanceAttendanceRecord::where('user_id',  auth()->id())->latest()->first();
                        // dd($checkin);
                        if (!is_null($lastRecord)) {
                            if ($checkin->attendance_type_id != 1) {
                                if (is_null($lastRecord->out)) {
                                    return true;
                                }
                            } else {
                                return false;
                            }
                        }
                    })
                    ->hidden(function (Get $get) {
                        $lastRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->where('attendance_type_id', $get('attendance_type_id'))->orderBy('updated_at', 'desc')->first();
                        if (!is_null($lastRecord)) {
                            if (!is_null($lastRecord->out)) {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    })
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                            if ($get('in')) {
                                $in = Carbon::create($get('in'));
                                if ($value) {
                                    $out = Carbon::create($value);
                                }
                                if (!$in->lt($out)) {
                                    $fail("Please select a date that is greater than in.");
                                }
                            }
                            $attendanceTypeRecord = AttendanceType::find($this->attendanceTypeId);
                            if ($attendanceTypeRecord->name != 'work') {
                                $lastBreakRecord = AttendanceAttendanceRecord::where('user_id',  $get('user_id'))->whereNotIn('attendance_type_id', [$this->attendanceTypeId])->orderBy('updated_at', 'desc')->first();
                                if ($lastBreakRecord) {
                                    if (!$lastBreakRecord->out) {
                                        Notification::make()
                                            ->title("You can't logout without finishing the break.")
                                            ->success()
                                            ->send();
                                        $fail("");
                                    }
                                }
                            }
                        },
                    ])
                    ->disabledOn('edit')
            ])
            ->statePath('break');
    }
    #[On('setOutDateTime')]
    public function setOutDateTime($dateTime)
    {
        $this->out = $dateTime;
    }
    #[On('setInDateTime')]
    public function setInDateTime($dateTime)
    {
        $this->in = $dateTime;
    }
    public function create(): void
    {
        $data = $this->form->getState();
        // dd($data);
        // $recipient = auth()->user();
        // dd( $recipient);
        $who = User::find($data['user_id']);
        // dd($who);
        $titl = "Check in - check out $who->name";
        $users = User::all();
        foreach ($users as $user) {
            Notification::make()
                ->title($titl)
                ->sendToDatabase($user);
        }
        $lastRecord = AttendanceAttendanceRecord::where('user_id', $data['user_id'])->where('attendance_type_id', $this->attendanceTypeId)->orderBy('updated_at', 'desc')->first();
        if (!is_null($lastRecord)) {
            if (is_null($lastRecord->out)) {
                $in = Carbon::parse($data['in']);
                $out = Carbon::parse($data['out']);
                $data['total_hours'] = $in->diffInHours($out);
                if ($data['in'] && $data['out']) {
                    $in = Carbon::parse($data['in']);
                    $out = Carbon::parse($data['out']);
                    $data['total_hours'] = $in->diffInHours($out);
                }
                AttendanceAttendanceRecord::where('id', $lastRecord->id)->update($data);
            } else {
                $data = $this->form->getState();
                $data['attendance_type_id'] = $this->attendanceTypeId;
                if (auth()->user()->hasRole('HR')) {
                    $data['status'] = 'approved';
                }
                if (auth()->user()->hasRole('Super Admin')) {
                    $data['status'] = 'approved';
                }
                AttendanceAttendanceRecord::create(
                    $data
                );
            }
        } else {
            $data = $this->form->getState();
            $data['attendance_type_id'] = $this->attendanceTypeId;
            if (auth()->user()->hasRole('HR')) {
                $data['status'] = 'approved';
            }
            if (auth()->user()->hasRole('Super Admin')) {
                $data['status'] = 'approved';
            }
            AttendanceAttendanceRecord::create(
                $data
            );
        }
        $this->form->fill();
        $this->dispatch('close-modal', id: 'createAttendance');
    }
    public function createBreak(): void
    {
        $data = $this->break;
        // dd($data);
        $lastRecord = AttendanceAttendanceRecord::where('user_id', $data['user_id'])->where('attendance_type_id', $data['attendance_type_id'])->orderBy('updated_at', 'desc')->first();
        if (!is_null($lastRecord)) {
            if (is_null($lastRecord->out)) {
                $in = Carbon::parse($data['in']);
                $out = Carbon::parse($data['out']);
                $data['total_hours'] = $in->diffInHours($out);
                if ($data['in'] && $data['out']) {
                    $in = Carbon::parse($data['in']);
                    $out = Carbon::parse($data['out']);
                    $data['total_hours'] = $in->diffInHours($out);
                }
                $data['status'] = 'approved';
                // dd($data['out'],$data['in']);
                unset($data['created_at']);
                unset($data['updated_at']);
                AttendanceAttendanceRecord::where('id', $lastRecord->id)->update($data);
            } else {
                $data['status'] = 'approved';
                AttendanceAttendanceRecord::create(
                    $data
                );
            }
        } else {
            $data['status'] = 'approved';
            AttendanceAttendanceRecord::create(
                $data
            );
        }
        $this->breakForm->fill();
        $this->dispatch('close-modal', id: 'createBreak');
    }
    public function openAttendanceRecord($attendanceType)
    {
        $this->attendanceTypeId = $attendanceType;
        $this->form->fill();
        $this->dispatch('open-modal', id: 'createAttendance');
    }
    public function openBreakRecord($attendanceType)
    {
        $this->attendanceTypeId = $attendanceType;
        $this->form->fill();
        $this->dispatch('open-modal', id: 'createBreak');
    }
    protected function getForms(): array
    {
        return [
            'form',
            'breakForm',
        ];
    }
}