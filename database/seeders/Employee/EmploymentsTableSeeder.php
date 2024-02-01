<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee\Employment;

class EmploymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all users
        $users = User::all();

        // Predefined data
        $employeeTypeIds = [1, 2, 3]; // Assuming these are valid employee type ids in your employee_types table
        $employeeStatusIds = [1, 1 , 1, 1]; // Assuming these are valid employee status ids in your employee_statuses table

        foreach ($users as $user) {
            Employment::create([
                'user_id' => $user->id,
                'employment_id' => 'EMP' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'hired_on' => now()->subYears(rand(1, 10)),
                'employee_type_id' => $employeeTypeIds[array_rand($employeeTypeIds)],
                'effective_date' => now(),
                'employee_status_id' => $employeeStatusIds[array_rand($employeeStatusIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
