<?php

namespace App\Filament\Resources\Employee\EmployeeResource\Pages;

use App\Filament\Resources\Employee\EmployeeResource;
use App\Models\Company\Company;
use App\Models\Onboarding\EmployeeOnboarding;
use App\Models\Onboarding\EmployeeOnboardingTask;
use App\Models\Onboarding\OnboardingTask;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Auth\ResetPassword;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected static ?string $title = 'Create Employee';

    public $onboard;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data['Onboarding']);
        $data['password'] = Hash::make(Str::random(10));
        $this->onboard = $data['Onboarding'];
        return $data;
    }
    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        $EmployeeOnboarding = EmployeeOnboarding::create([
            'user_id' => $record->id,
            'onboarding_list_id' => $this->onboard,
            'comment' => null,
        ]);
      $OnboardingTask = OnboardingTask::where('onboarding_list_id',$this->onboard)->get();
      foreach ($OnboardingTask as $OnboardingTasks) {
        EmployeeOnboardingTask::create([
            'employee_onboarding_id' => $EmployeeOnboarding->id,
            'onboarding_task_id' => $OnboardingTasks->id,
            'comment' => null,
        ]);
        $recipient = User::where('id', $OnboardingTasks->user_id)->get();
        Notification::make()
            ->title('Onboarding')
            ->body("Your onboarding has been started")
            ->actions([
                Action::make('view')
                    ->button()->url('/employees/'.$OnboardingTasks->user_id.'/edit?activeRelationManager=6')->close()
            ])
            ->sendToDatabase($recipient);
      }
      $recipient = User::where('id', $record->id)->get();
            Notification::make()
                ->title('Onboarding')
                ->body("A new onboarding task has been assigned ")
                ->actions([
                    Action::make('view')
                        ->button()->url('/employees/'.$record->id.'/edit?activeRelationManager=6')->close()
                ])
                ->sendToDatabase($recipient);

 // sending password reset link
            $company = Company::whereHas('employee', function ($query) use ($record) {
                $query->where('user_id', $record->id);
            })->first();
            $data["hr"] = '';
            if ($company) {
                $data["company"] = $company->name;
            }
            $hr = User::whereHas('roles', function ($query) {
                $query->where('name', 'HR');
            })->first();
            if ($hr) {
                $data["hr"] = $hr;
            }
            // generate password reset link
            $token = app('auth.password.broker')->createToken($record);
            $notification = new ResetPassword($token);
            $notification->url = Filament::getResetPasswordUrl($token, $record);
            $data["email"] = $record->email;
            $data["title"] = " Welcome to " . $company->name . '!';
            $data["name"] =  $record->last_name ? $record->name . ' ' . $record->last_name : $record->name;
            $data["url"] =  $notification->url;
            Mail::send('loginTemplate', $data, function ($message) use ($data) {
                $message->to($data["email"])

                    ->subject($data["title"]);
            });
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
