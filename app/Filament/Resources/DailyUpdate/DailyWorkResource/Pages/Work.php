<?php

namespace App\Filament\Resources\DailyUpdate\DailyWorkResource\Pages;

use App\Filament\Resources\DailyUpdate\DailyWorkResource;
use App\Models\DailyUpdate\DailyWork;
use App\Models\DailyUpdate\Date;
use Carbon\Carbon;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class Work extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = DailyWorkResource::class;

    protected static string $view = 'filament.resources.daily-update.daily-work-resource.pages.work';

    public $record;
    public $dailyworks;
    public $content;
    public $currentData;
    public $auth;
    public $dateValue;
    public $workId;
    public $firstLetterUpper;
    public $isCurrentDate;
    public ?array $work = [];
    public ?array $daily = [];


    public function workForm(Form $form): Form 
    {
     
        // dd($this->record);
        $currentDate = Carbon::now()->format("Y-m-d");
        $date = Date::where('id', $this->record)->first();
        // dd($date->date);
        $givenDate = $date->date;
        $this->isCurrentDate = Carbon::parse($givenDate)->equalTo(Carbon::parse($currentDate));
        // dd($isCurrentDate);

        return $form
            ->schema([
                RichEditor::make('content')
                    ->label('')
                    ->required()
                    ->columnSpanFull()
                    ->visible($this->isCurrentDate),
            ])
            ->statePath('work')
            ->model(DailyWork::class);
    }

    public function dailyForm(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('content')
                    ->label('')
                    ->required()
                    ->columnSpanFull()
                    ->default($this->currentData),
            ])
            ->statePath('daily')
            ->model(DailyWork::class);
    }

    protected function getForms(): array
    {
        return [
            'workForm',
            'dailyForm',
        ];
    }

    public function createWork()
    {
        //  dd($this->workForm->getState());
        $this->auth = auth()->user()->id;
        // dd($auth);
        $this->content = $this->workForm->getState();
        // dd($this->content['content']);
        // dd($this->record);

        // dd($a);

        $work = DailyWork::create([
            'user_id' => $this->auth,
            'date_id' => $this->record,
            'content' => $this->content['content'],
        ]);

      
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
        
        // $this->dailyworks = DailyWork::with('user')->orderBy('created_at', 'desc')->get();

        $this->dailyworks = DailyWork::with('user', 'date')->where('date_id', $this->record)->orderBy('created_at', 'desc')->get();
        // dd($this->dailyworks);

        $this->workForm->fill();

    }

    public function editWork($id)
    {
        // dd($id);
        $this->workId = $id;
        $test = DailyWork::find($id);
        // dd($test);
        // dd($this->currentData);
        // $this->workForm->fill();
        // $this->dailyForm->fill();
        $this->dailyForm->fill($test->toArray());

        $this->dailyworks = DailyWork::with('user', 'date')->where('date_id', $this->record)->orderBy('created_at', 'desc')->get();
    }

    public function updateWork()
    {
        $updateWork = DailyWork::find($this->workId);
        $workId = $this->daily;
        // dd($updateWork);
        // dd($updateWork->user_id);
        // dd($updateWork->content);
        $updateWork->update([
            'user_id' => $updateWork->user_id,
            'content' => $workId['content'],
        ]);

        // dd($updateWork);

        Notification::make()
            ->title('Updated successfully')
            ->success()
            ->send();

        $this->dailyworks = DailyWork::with('user', 'date')->where('date_id', $this->record)->orderBy('created_at', 'desc')->get();
    }

    public function getHeading(): string
    {
        if ($this->isCurrentDate) {
            return 'What did you work on yesterday, and what are you planning to work on today?';
        }
        else{
            return 'The Work Progress';
        }

    }

    public function mount(): void
    {
        // dd($this->record);
        $this->dailyworks = DailyWork::with('user.jobInfo.designation', 'date')->where('date_id', $this->record)->orderBy('created_at', 'desc')->get();
        // dd($this->dailyworks[0]->user->jobInfo->designation->name);
        // dd($this->dailyworks);
        // dd($this->dailyworks[0]->created_at);
        // dd($this->dailyworks[0]->user->id);
        $this->workForm->fill();
        $this->dailyForm->fill();
        static::authorizeResourceAccess();
    }












}
