<?php

namespace App\Filament\Resources\Timesheet\ProjectResource\Pages;

use App\Filament\Resources\Timesheet\ProjectResource;
use App\Models\Employee\Team;
use App\Models\User;
use App\Settings\GeneralSettings;
// use Filament\Actions\Modal\Actions\Action;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateProject extends CreateRecord
{


    protected static string $resource = ProjectResource::class;

    public $notification;
    public $emailNotification;

    protected function afterCreate(): void
    {

        $generalSetting = app(GeneralSettings::class);
        $this->notification =  $generalSetting->app_notification;
        $this->emailNotification = $generalSetting->email_notification;

        $record = $this->getRecord();
        // dd($record);
        if ($this->notification) {
            $project = User::whereHas('jobInfo.team', function ($query) use ($record) {
                $query->where('id', $record->team_id);
            })->get();
            // dd($project);
            foreach ($project as $projects) {
                $recipient = User::where('id', $projects->id)->get();
                dd($recipient);
                Notification::make()
                    ->title('New project is assigned to your team ')
                    ->actions([
                        Action::make('view')
                            ->button()->url('/timesheet/projects/' . $record->id . '/edit')
                            ->close()
                    ])
                    ->sendToDatabase($recipient);
            }
        }

        if ($this->emailNotification) {
            $projects = User::whereHas('jobInfo.team', function ($query) use ($record) {
                $query->where('id', $record->team_id);
            })->get();
            // dd($recipients);
            $emailnotifications = $recipient;

            foreach ($projects as $project) {
                $recipient = User::where('id', $project->id)->get();
                // dd($recipient->email);
                // $data=['name'=>'sandhiya@saasforest.com'];
                $value = ['name' => 'New project is assigned to your team','url'=>config('app.url').'/timesheet/projects'];
                Mail::send('emailnotification', $value, function ($message) use ($recipient) {
                    $message->to($recipient['email'])
                        ->subject('HRMS CONNECT');
                });
            }
        }
    }
}
