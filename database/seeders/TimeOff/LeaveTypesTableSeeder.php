<?php

namespace Database\Seeders\TimeOff;

use Illuminate\Database\Seeder;
use App\Models\TimeOff\LeaveType;

class LeaveTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leaveTypes = [
            [
                'name' => 'Annual Leave',
                'days_allowed' => 14,
                'frequency' => 'annually',
                'auto_approve' => false,
                'color' => '#0000FF',
                'gender_id' => null,
            ],
            [
                'name' => 'Sick Leave',
                'days_allowed' => 7,
                'frequency' => 'annually',
                'auto_approve' => false,
                'color' => '#00FF00',
                'gender_id' => null,
            ],
            [
                'name' => 'Maternity Leave',
                'days_allowed' => 60,
                'frequency' => 'annually',
                'auto_approve' => true,
                'color' => '#FF00FF',
                'gender_id' => 2, // Assuming 2 is the ID for female gender
            ],
            [
                'name' => 'Paternity Leave',
                'days_allowed' => 15,
                'frequency' => 'annually',
                'auto_approve' => true,
                'color' => '#0000FF',
                'gender_id' => 1, // Assuming 1 is the ID for male gender
            ],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::create($type);
        }
    }
}
