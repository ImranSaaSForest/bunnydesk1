<?php

namespace App\Filament\Resources\DailyUpdate\DateResource\Pages;

use App\Filament\Resources\DailyUpdate\DateResource;
use App\Models\DailyUpdate\DailyWork;
use App\Models\DailyUpdate\Date;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class DailyWorkPopup extends Page implements HasForms
{

    use InteractsWithForms;
    
    protected static string $resource = DateResource::class;

    protected static string $view = 'filament.resources.daily-update.date-resource.pages.daily-work-popup';

    public $auth;
    public $content;
    public $dailyworks;
    public ?array $daily = [];

    public function dailyForm(Form $form): Form
    {
        return $form
            ->schema([
                RichEditor::make('content')
                    ->label('')
                    ->required()
                    ->columnSpanFull()
            ])
            ->statePath('daily')
            ->model(DailyWork::class);
    }

    protected function getForms(): array
    {
        return [
            'dailyForm',
        ];
    }

    public function createWork()
    {

        $currentDate = date("Y-m-d");
        // dd($currentDate);

        $date = Date::where('date', $currentDate)->first();
        // dd($date->id);

        $this->auth = auth()->user()->id;
        // dd($auth);
        $this->content = $this->dailyForm->getState();
        // dd($this->content['content']);
        // dd($this->record);

        // dd($a);

        $work = DailyWork::create([
            'user_id' => $this->auth,
            'date_id' => $date->id,
            'content' => $this->content['content'],
        ]);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();

        return redirect()->route('filament.admin.resources.dates.edit', ['record' => $date->id]);

        // $this->dailyworks = DailyWork::with('user')->orderBy('created_at', 'desc')->get();

        $this->dailyworks = DailyWork::with('user', 'date')->where('date_id', $this->record)->orderBy('created_at', 'desc')->get();
        // dd($this->dailyworks);

        $this->dailyForm->fill();

    }

    public function cancel(){
        return redirect()->route('filament.admin.resources.dates.index');
    }

    public function getHeading(): string
    {
        return 'What did you work on yesterday, and what are you planning to work on today?';
    }

    public function mount(): void
    {

        $this->dailyForm->fill();

        static::authorizeResourceAccess();
    }
}
