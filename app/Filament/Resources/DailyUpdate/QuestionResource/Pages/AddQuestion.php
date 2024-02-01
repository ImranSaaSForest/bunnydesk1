<?php

namespace App\Filament\Resources\DailyUpdate\QuestionResource\Pages;

use App\Filament\Resources\DailyUpdate\QuestionResource;
use App\Models\DailyUpdate\Day;
use App\Models\DailyUpdate\Question;
use App\Models\DailyUpdate\TaskUser;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Validate;

class AddQuestion extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = QuestionResource::class;

    protected static string $view = 'filament.resources.daily-update.question-resource.pages.add-question';

    public $dailys;
    public $days;
    public $times;
    public $weekDay;
    public $monthDay;
    public $startTime;
    public $endTime;
    public $startTimeCheck;
    public $endTimeCheck;
    public $dayCollection;
    public $myArray = [];
    public $users;
    public $record;
    public $passValue;
    #[Validate('required')]
    public $description;

    #[Validate('required')]
    public $status;

    public $day;

    public $userName;
    public $sel;
    public ?array $data = [];
    public $editQus;
    public $date;
    public $selectTime;
    public $updateVal;
    public $idCollection = [];

    public function form(Form $form): Form
    {
        $options = [];
        // dd($this->idCollection);
        $taskUser = TaskUser::with('question')->where('question_id', $this->record)->get();
        foreach ($taskUser as $task) {
            $names = User::find($task->user_id);
            $this->idCollection[] = $names->name;
        }

        // dd($this->idCollection);
        if ($this->idCollection == []) {
            $options = User::all()->pluck('name', 'id');
        } else {
            // dd('Velu');
            $options = User::whereNotIn('id', $this->idCollection)->pluck('name', 'id');
        }

        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Who do you want to ask?')
                    ->multiple()
                    ->options($options)
                    ->searchable()
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function getForms(): array
    {
        return [
            'form',
        ];
    }

    public function dailyStatus($key)
    {
        // dd($day);
        // array_push($this->myArray, $key);

        if (in_array($key, $this->myArray)) {
            $this->myArray = array_values(array_diff($this->myArray, [$key]));
        } else {
            array_push($this->myArray, $key);
        }
    }

    public function dayCollect($day)
    {
        $this->weekDay = $day;
        // dd($this->weekDay);
    }

    public function createQuestion()
    {

        // dd($this->startTimeCheck);
        // dd($this->monthDay);

        // dd($this->weekDay);

        // dd($this->myArray);

        $this->validate();

        $question = Question::create([
            'title' => $this->description,
            'frequency' => $this->status,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
        ]);
        // dd($question);

        $userData = $this->form->getState();
        // dd($userData['user_id']);

        // $this->passValue = $this->description;

        foreach ($userData['user_id'] as $b) {
            TaskUser::create([
                'user_id' => $b,
                'question_id' => $question->id,
            ]);
        }

        if ($this->status == 'Daily On') {
            // dd('Velu');
            foreach ($this->myArray as $b) {
                // dd($b);
                Day::create([
                    'day' => $b,
                    'question_id' => $question->id,
                ]);
            }
        } elseif ($this->status == 'Once a Week') {
            // dd('Muthu');
            Day::create([
                'day' => $this->weekDay,
                'question_id' => $question->id,
            ]);
        } elseif ($this->status == 'Once a month on the first') {
            // dd('Velsamy');
            Day::create([
                'day' => $this->weekDay,
                'question_id' => $question->id,
            ]);
        }

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();

        $this->clearQuestion();

        return redirect()->route('filament.admin.resources.daily-update.questions.index');
    }

    public function editQuestion($id)
    {
        $this->editQus = Question::find($id);
        // dd($this->editQus);
        $taskUser = TaskUser::with('question')->where('question_id', $id)->get();
        $questionDays = Day::with('question')->where('question_id', $id)->get();
        // dd($questionDays);
        // dd($taskUser);
        $this->description = $this->editQus->title;
        $this->status = $this->editQus->frequency;
        $this->startTime = $this->editQus->start_time;
        $this->endTime = $this->editQus->end_time;
        // $this->day = $this->editQus->time;
        // $this->day1 = $this->a->time;
        $this->updateVal = true;
        $nameCollection = [];
        // dd($taskUser);
        foreach ($taskUser as $task) {
            $names = User::find($task->user_id);
            // dd($names);
            $nameCollection[] = $names->id;
            // dd($nameCollection);
        }

        $daysCollection = [];
        foreach ($questionDays as $questionDay) {
            $selectedDay = Day::find($questionDay->id);
            // dd($selectedDay);
            $daysCollection[$selectedDay->day] = $selectedDay->day;
        }
        $this->myArray = $daysCollection;
        // array_push($daysCollection);
        // dd($this->myArray );
        // dd($nameCollection);
        $this->form->fill(['user_id' => $nameCollection]);

        if ($this->startTime) {
            $this->startTimeCheck = true;
        }

        if ($this->endTime) {
            $this->endTimeCheck = true;
        }

        // dd($nameCollection);

    }

    public function updateQuestion()
    {
        // dd($this->record);
        $updateQuestion = Question::find($this->record);
        // dd($updateQuestion);
        // dd($this->day);
        // $userData = $this->form->getState();
        // dd($userData);

        if ($this->status == 'Daily On') {
            // dd('Velu');
            if ($this->startTimeCheck && $this->endTimeCheck) {
                // dd($this->startTimeCheck);
                // dd('Velu');
                $updateQuestion->update([
                    'title' => $this->description,
                    'frequency' => $this->status,
                    'start_time' => $this->startTime,
                    'end_time' => $this->endTime,
                ]);
            } elseif (!$this->startTimeCheck) {
                // dd('Muthu');
                $updateQuestion->update([
                    'title' => $this->description,
                    'frequency' => $this->status,
                    'start_time' => null,
                    'end_time' => $this->endTime,
                ]);
            } elseif (!$this->endTimeCheck) {
                // dd('velsamy');
                $updateQuestion->update([
                    'title' => $this->description,
                    'frequency' => $this->status,
                    'start_time' => $this->startTime,
                    'end_time' => null,
                ]);
            }
        }

        $userData = $this->form->getState();
        // dd($userData['user_id']);

        if ($this->status == 'Daily On') {
            // dd('Velu');
            foreach ($this->myArray as $b) {
                // $c[] = $b;
                // dd($b);

                $existingDay = Day::with('question')->where('day', $b)->where('question_id', $updateQuestion->id)->first();
                // dd($existingDay);

                // If the days does not exist, perform the update
                if (!$existingDay) {
                    Day::create([
                        'question_id' => $updateQuestion->id,
                        'day' => $b
                    ]);
                }
            }
            // dd($c);
        }

        // Get all days associated with the question
        $existingDays = Day::where('question_id', $updateQuestion->id)->pluck('day')->toArray();

        // Find days that need to be deleted
        $daysToDelete = array_diff($existingDays, $this->myArray);

        // Delete records for days that are in the database but not in the form state
        Day::where('question_id', $updateQuestion->id)
            ->whereIn('day', $daysToDelete)
            ->delete();

        foreach ($userData['user_id'] as $userId) {
            // Check if the user_id already exists
            $existingUser = TaskUser::where('user_id', $userId)->where('question_id', $updateQuestion->id)->first();
            // dd($existingUser);

            // If the user does not exist, perform the update
            if (!$existingUser) {
                TaskUser::create([
                    'user_id' => $userId,
                    'question_id' => $updateQuestion->id,
                ]);
            }
        }

        // Delete records for users that are in the database but not in the form state
        $v = TaskUser::whereNotIn('user_id', $userData['user_id'])->where('question_id', $updateQuestion->id)
            ->delete();
        // dd($v);

        Notification::make()
            ->title('Updated successfully')
            ->success()
            ->send();

        $this->clearQuestion();
    }

    public function clearQuestion()
    {

        $this->description = '';
        $this->status = '';
        $this->day = '';
        $this->selectTime = '';
        $this->form->fill();
    }

    public function mount(): void
    {

        $taskUser = TaskUser::with('question')
            ->whereHas('question', function ($query) {
                $query->where('frequency', 'Daily On');
            })
            ->get();

        $dayArray = Day::with('question')
            ->whereHas('question', function ($query) {
                $query->where('frequency', 'Daily On');
            })
            ->get();
        // dd($taskUser);

        // dd($dayArray);
        $this->users = User::with('jobInfo.designation')->get();
        // dd($this->users[1]->jobInfo->designation->name);
        $this->dailys = ['Daily On', 'Once a Week', 'Once a month on the first'];

        $this->days = ['sunday' => 'Sun', 'monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed', 'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat'];

        $this->form->fill();

        if ($this->record != null) {
            $this->editQuestion($this->record);
        }

        static::authorizeResourceAccess();


        // if (true) {
        //     $vals = Question::with('day')->get();
        //     foreach($vals as $val){
        //         // dd($val->id);
        //         $day = Day::where('id', $val->id)->get()->toArray();
        //         dd($day);
        //     }
        // }
        // if (true) {
        //     $today = Carbon::now()->format('l');
        //     // Fetch questions records from the days table where today's day name is present
        //     $questions = Question::whereHas('day', function ($query) use ($today) {
        //         $query->where('day', $today);
        //     })->get();
        //     // dd($questions);
        //     foreach($questions as $question){
        //         dd($question);
        //     }
        // }
    }
}
