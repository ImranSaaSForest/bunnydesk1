<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee\Employee;

class EmployeesTableSeeder extends Seeder
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
        $genderIds = [1, 2]; // Assuming 1 and 2 are valid gender ids in your genders table
        $companyIds = [1];
        $maritalStatusIds = [1, 2, 3]; // Assuming these are valid marital status ids in your marital_statuses table
        $timezones = ['America/New_York', 'Europe/Paris', 'Asia/Kolkata'];

        foreach ($users as $user) {
            Employee::create([
                'user_id' => $user->id,
                'company_id' => $companyIds[array_rand($companyIds)],
                'date_of_birth' => '1990-01-01',
                'gender_id' => $genderIds[array_rand($genderIds)],
                'marital_status_id' => $maritalStatusIds[array_rand($maritalStatusIds)],
                'social_security_number' => '123-45-6789',
                'timezone' => $timezones[array_rand($timezones)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
