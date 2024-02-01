<?php

namespace Database\Seeders\TimeOff;

use Illuminate\Database\Seeder;
use App\Models\Company\Company;
use App\Models\TimeOff\WorkWeek;
use App\Models\TimeOff\Holiday;
use App\Models\TimeOff\Policy;
use App\Models\TimeOff\PolicyFrequency;
use App\Models\TimeOff\LeaveType;
use App\Models\Employee\Team;
use App\Models\TimeOff\PolicyLeaveType;
use Illuminate\Support\Facades\DB;

class PoliciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // assuming these resources are already seeded
        $companies = Company::all();
        $teams = Team::all();
        $workWeeks = WorkWeek::all();
        $holidays = Holiday::all();
        $leaveTypes = LeaveType::all();

        foreach ($companies as $company) {
            $policy = Policy::create([
                'name' => 'Policy for ' . $company->name,
                'description' => 'Policy Description',
                'start_date' => '2023-01-01',
                'is_active' => true,
                'work_week_id' => $workWeeks->random()->id,
                'holiday_id' => $holidays->random()->id,
            ]);

            // Attach policy to random companies
            $policy->companies()->attach(
                $companies->random(rand(1, $companies->count()))->pluck('id')->toArray()
            );

            // Attach policy to random teams
            $policy->teams()->attach(
                $teams->random(rand(1, $teams->count()))->pluck('id')->toArray()
            );

            foreach ($leaveTypes as $leaveType) {
                PolicyLeaveType::create([
                    'leave_type_id' => $leaveType->id,
                    'days_allowed' => rand(10, 20),
                    'frequency' => 'annually',
                    'policy_id' => $policy->id,
                ]);
            }
        }
    }
}
