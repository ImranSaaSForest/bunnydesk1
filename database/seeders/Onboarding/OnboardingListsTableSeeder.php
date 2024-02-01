<?php

namespace Database\Seeders\Onboarding;

use Illuminate\Database\Seeder;
use App\Models\Onboarding\OnboardingList;
use App\Models\Onboarding\OnboardingTask;
use App\Models\User;

class OnboardingListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming you have a User with id=1
        $user_id = 1;

        $onboardingLists = [
            [
                'title' => 'HR Onboarding',
                'description' => 'List of tasks for onboarding new HR employees.',
                'is_active' => true,
            ],
            [
                'title' => 'Engineer Onboarding',
                'description' => 'List of tasks for onboarding new Software Engineers.',
                'is_active' => true,
            ],
            // add more lists as needed
        ];

        foreach ($onboardingLists as $list) {
            $onboardingList = OnboardingList::create($list);

            // Create tasks for each list
            OnboardingTask::create([
                'onboarding_list_id' => $onboardingList->id,
                'name' => 'Complete documentation',
                'user_id' => $user_id,
                'duration' => 2,
                'description' => 'Complete the necessary documentation.',
            ]);

            OnboardingTask::create([
                'onboarding_list_id' => $onboardingList->id,
                'name' => 'System setup',
                'user_id' => $user_id,
                'duration' => 1,
                'description' => 'Setup necessary systems and software.',
            ]);

            // Add more tasks as needed
        }
    }
}
