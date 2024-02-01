<?php

namespace App\Filament\Resources\Onboarding\OnboardingResource\Pages;

use App\Filament\Resources\Onboarding\OnboardingResource;
use App\Models\Onboarding\EmployeeOnboarding;
use App\Models\Onboarding\EmployeeOnboardingTask;
use App\Models\Onboarding\OnboardingTask;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageOnboardings extends ManageRecords
{
    protected static string $resource = OnboardingResource::class;

    protected static ?string $title = 'Onboarding Assignments';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Assign Onboarding')
                ->using(function (array $data): Model {

                    // First create the employee_onboarding record with the user_id and onboarding_list_id set
                    $employeeOnboarding = EmployeeOnboarding::create([
                        'user_id' => $data['user_id'],
                        'onboarding_list_id' => $data['onboarding_list_id'],
                        'comment' => $data['comment'] ?? null,
                    ]);

                    // Fetch the onboarding tasks based on the selected onboarding list
                    $onboardingTasks = OnboardingTask::where('onboarding_list_id', $data['onboarding_list_id'])->get();

                    // Create an employee_onboarding_task record for each onboarding task
                    foreach ($onboardingTasks as $onboardingTask) {
                        EmployeeOnboardingTask::create([
                            'employee_onboarding_id' => $employeeOnboarding->id,
                            'onboarding_task_id' => $onboardingTask->id,
                            'status' => false,
                            'comment' => null,
                        ]);
                    }

                    return $employeeOnboarding;
                }),
        ];
    }
}
