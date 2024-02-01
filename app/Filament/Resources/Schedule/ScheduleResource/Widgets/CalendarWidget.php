<?php

namespace App\Filament\Resources\Schedule\ScheduleResource\Widgets;

// use Filament\Widgets\Widget;
use App\Filament\Resources\Schedule\ScheduleResource;
use App\Models\Schedule\Schedule;
use App\Models\Schedule\ScheduleWith;
use App\Models\User;
use Carbon\Carbon;
// use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
// use Saade\FilamentFullCalendar\Actions\CreateAction;
// use Saade\FilamentFullCalendar\Data\EventData;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Saade\FilamentFullCalendar\Data\EventData;

class CalendarWidget extends FullCalendarWidget
{
    // protected static string $view = 'filament.resources.schedule.schedule-resource.widgets.calendar-widget';
    public Model | string | null $model = Schedule::class;

    public function fetchEvents(array $fetchInfo): array
    {
        // dd($fetchInfo);
        return Schedule::query()
            ->where('event_date', '>=', $fetchInfo['start'])
            // ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Schedule $event) => [
                    'title' => $event->name,
                    'start' => $event->event_date,
                    'end' => $event->event_date,
                    'name' => $event->name,
                    'url' => ScheduleResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }
    public function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->label('Event Name'),
                    DatePicker::make('event_date')
                        ->required()
                        ->native(false),
                ]),
            Grid::make()
                ->schema([
                    TimePicker::make('event_time')
                        ->required()
                        ->minDate(now())
                        ->seconds(false),
                    TimePicker::make('notify_at')
                        ->helperText('The Notification is set on the event date selected')
                        ->seconds(false)
                        ->datalist([
                            '09:00',
                            '09:30',
                            '10:00',
                            '10:30',
                            '11:00',
                            '11:30',
                            '12:00',
                            '12:30',
                            '13:00',
                            '13:30',
                            '14:00',
                            '14:30',
                            '15:00',
                            '15:30',
                            '16:00',
                            '16:30',
                            '17:00',
                            '17:30',
                            '18:00',
                            '18:30',
                            '19:00',
                            '19:30',
                            '20:00',
                            '20:30',
                            '21:00'
                        ]),

                ]),
            Select::make('technologies')
                ->multiple()
                ->label('Schedule with:')
                ->options(function () {
                    return User::where('id', '!=', Auth()->id())->pluck('name', 'id');
                }),
            Textarea::make('about')
                ->required()
                ->columnSpanFull(),

        ];
    }
    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        // dd($arguments['start']);
                        $form->fill([
                            'event_date' => $arguments['start'] ?? null,
                            // 'ends_at' => $arguments['end'] ?? null
                        ]);
                    }
                )
                ->after(function ($record, $data) {
                    // dd($data);
                    foreach ($data['technologies'] as $guest) {
                        ScheduleWith::create([
                            'schedule_id' => $record->id,
                            'user_id' => $guest,
                        ]);
                        $recipient = auth()->user();
                        $user = User::find($guest);
                        // dd($recipient);
                        Notification::make()
                            ->title($recipient['name'] . ' Has created a Meeting with you at ' . Carbon::parse($data['event_date'] . ', ' . $data['event_time'])
                                ->format('F j, Y, g:i A'))
                            ->actions([
                                Action::make('view')
                                    ->button()
                                    ->url('schedule/schedules/' . $record->id)
                            ])->sendToDatabase($user);
                    }
                })
            // ->mutateFormDataUsing(function (array $data): array {
            //     return [
            //         ...$data,
            //         'created_by' => $this->record->id
            //     ];
            // })
        ];
    }
}
