<?php

namespace Database\Seeders\Offboarding;

use Illuminate\Database\Seeder;
use App\Models\Offboarding\OffboardingList;
use App\Models\Offboarding\OffboardingTask;
use App\Models\User;

class OffboardingListsTableSeeder extends Seeder
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

        $offboardingLists = [
            [
                'title' => 'HR Offboarding',
                'description' => 'List of tasks for offboarding HR employees.',
                'is_active' => true,
            ],
            [
                'title' => 'Engineer Offboarding',
                'description' => 'List of tasks for offboarding Software Engineers.',
                'is_active' => true,
            ],
            // add more lists as needed
        ];

        foreach ($offboardingLists as $list) {
            $offboardingList = OffboardingList::create($list);

            // Create tasks for each list
            OffboardingTask::create([
                'offboarding_list_id' => $offboardingList->id,
                'name' => 'Return company equipment',
                'user_id' => $user_id,
                'duration' => 1,
                'description' => 'Return all company-provided equipment.',
            ]);

            OffboardingTask::create([
                'offboarding_list_id' => $offboardingList->id,
                'name' => 'Exit interview',
                'user_id' => $user_id,
                'duration' => 2,
                'description' => 'Conduct an exit interview to provide feedback.',
            ]);

            // Add more tasks as needed
        }
    }
}
