<?php

namespace Database\Seeders\TimeOff;

use Illuminate\Database\Seeder;
use App\Models\TimeOff\WorkWeek;
use App\Models\TimeOff\WorkWeekDay;

class WorkWeekAndDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Work weeks to be seeded
        $workWeeks = [
            ['name' => 'Normal Week', 'week_start_day' => 'Monday', 'is_active' => true],
            ['name' => 'Alternative Week', 'week_start_day' => 'Sunday', 'is_active' => false],
            // add more work week patterns here...
        ];

        // Assuming the work week days are the same for each work week
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Create work weeks and their respective days
        foreach ($workWeeks as $week) {
            $workWeek = WorkWeek::create($week);

            foreach ($days as $day) {
                WorkWeekDay::create([
                    'day' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'work_week_id' => $workWeek->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
