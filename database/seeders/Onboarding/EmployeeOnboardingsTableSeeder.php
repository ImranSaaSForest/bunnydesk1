<?php

namespace Database\Seeders\Onboarding;

use Illuminate\Database\Seeder;
use App\Models\Onboarding\EmployeeOnboarding;
use App\Models\Onboarding\EmployeeOnboardingTask;
use App\Models\User;
use App\Models\Onboarding\OnboardingList;
use App\Models\Onboarding\OnboardingTask;

class EmployeeOnboardingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all users and onboarding lists
        $users = User::all();
        $onboardingLists = OnboardingList::all();

        foreach ($users as $user) {
            foreach ($onboardingLists as $onboardingList) {
                // Create an onboarding record for each user-list combination
                $employeeOnboarding = EmployeeOnboarding::create([
                    'user_id' => $user->id,
                    'onboarding_list_id' => $onboardingList->id,
                    'comment' => "Onboarding comment for user {$user->id} and list {$onboardingList->id}",
                ]);

                // Fetch all tasks in the onboarding list
                $onboardingTasks = OnboardingTask::where('onboarding_list_id', $onboardingList->id)->get();

                foreach ($onboardingTasks as $onboardingTask) {
                    // Create an onboarding task record for each task in the list
                    EmployeeOnboardingTask::create([
                        'employee_onboarding_id' => $employeeOnboarding->id,
                        'onboarding_task_id' => $onboardingTask->id,
                        'status' => 'completed',
                        'comment' => "Onboarding task comment for task {$onboardingTask->id}",
                    ]);
                }
            }
        }
    }
}
