<?php

namespace App\Console;

use App\Models\DailyUpdate\Date;
use App\Models\DailyUpdate\Day;
use App\Models\DailyUpdate\Question;
use App\Models\DailyUpdate\TaskUser;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use App\Models\Schedule\Schedule as Shed;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Throwable;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */


    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            // dd(auth()->id());
            $recipient = User::find(1);
            $notify = Shed::all();
            foreach ($notify as $notifys) {
                $eventDate = Carbon::parse($notifys->event_date);
                $notifyAt = Carbon::parse($notifys->notify_at);
                $eventTime = date('h:i A', strtotime($notifys->event_time));
                $now = Carbon::now();
                $Author = User::where('id', $notifys->created_by)->first();


                if ($eventDate->isSameDay($now) && $notifyAt->format('H:i') === $now->format('H:i')) {
                    Notification::make()
                        // ->title('Reminder for the Meeting Scheduled at '. $notifys->event_time->format('H:i A'))
                        ->title('Reminder for ' . $notifys->name . ' Scheduled at ' . $eventTime . ' Today')
                        ->sendToDatabase($Author);
                } else {
                    // dd('nope');
                }
            }
        })->everyMinute();

        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            Date::create([
                'date' => now()
            ]);
        })->dailyAt('01:00');

        // Daily On
        $dailyTaskUsers = TaskUser::with('users', 'question')
            ->whereHas('question', function ($query) {
                $query->where('frequency', 'Daily On');
            })
            ->get();

        $dayArrays = Day::with('question')
            ->whereHas('question', function ($query) {
                $query->where('frequency', 'Daily On');
            })
            ->get();

        foreach ($dailyTaskUsers as $taskUser) {
            // dd($dailyTaskUsers->toArray()[0]);
            $dailyStartTime = $taskUser->question->start_time;
            $dailyEndTime = $taskUser->question->end_time;
            // dd($dailyEndTime);

            if ($dailyStartTime) {
                $startTimeFormat = DateTime::createFromFormat('H:i:s', $dailyStartTime);
                $startTime = $startTimeFormat->format('H:i');
            }
            // dd($startTimeFormat);

            if ($dailyEndTime) {
                $endTimeFormat = DateTime::createFromFormat('H:i:s', $dailyEndTime);
                $endTime = $endTimeFormat->format('H:i');
            }
            // dd($endTimeFormat);

            $specificDays = $dayArrays->pluck('day')->toArray();
            // dd($specificDays);

            // Map day names to numerical values
            $numericalDays = array_map(function ($day) {
                return date('N', strtotime($day));
            }, $specificDays);
            // dd($numericalDays);
            $today = date('l');
            $todaylc = lcfirst($today);
            // dd($todaylc);
            // dd($today, $specificDays, $todaylc);
            $dayNumber = date('N', strtotime($today));
            // dd($dayNumber);
            // foreach ($specificDays as $specday) {
            if ($dailyStartTime) {
            }
            // }
        }

        // Start Time
        if (true) {
            $today = Carbon::now()->format('l');
            // Fetch questions records from the days table where today's day name is present
            $questions = Question::whereHas('day', function ($query) use ($today) {
                $query->where('day', $today);
            })->get();

            foreach ($questions as $question) {
                $usr = TaskUser::where('question_id',$question->id)->first();
                $userID = User::find($usr->user_id);

                $ques = $question->title;
                if (Carbon::now()->format('H:i:s') == $question->start_time) {
                    $schedule->call(function () use ($taskUser, $question, $userID)  {
                        $sendNotification = $taskUser->users;

                        Notification::make()
                            ->success()
                            ->title($question->title)
                            ->actions([
                                Action::make('view')
                                    ->button()
                                    ->url("/dates/popup"),
                            ])
                            ->sendToDatabase($userID);
                    })->everyMinute();
                }
            }
        }

        // End Time
        if (true) {
            $today = Carbon::now()->format('l');
            // Fetch questions records from the days table where today's day name is present
            $questions = Question::whereHas('day', function ($query) use ($today) {
                $query->where('day', $today);
            })->get();
           
            foreach ($questions as $question) {
                $usr = TaskUser::where('question_id',$question->id)->first();
                $userID = User::find($usr->user_id);

                $ques = $question->title;
                $ques = $question->title;
                if (Carbon::now()->format('H:i:s') == $question->end_time) {
                    $schedule->call(function () use ($taskUser, $question, $userID)  {
                        $sendNotification = $taskUser->users;
                        Notification::make()
                            ->success()
                            ->title($question->title)
                            ->actions([
                                Action::make('view')
                                    ->button()
                                    ->url("/dates/popup"),
                            ])
                            ->sendToDatabase($userID);
                    })->everyMinute();
                }
            }
        }

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
