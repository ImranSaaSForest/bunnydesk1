<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee\JobInfo;

class JobInfosTableSeeder extends Seeder
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
        $designationIds = [1, 2, 3]; // Assuming these are valid designation ids in your designations table
        $teamIds = [1, 2, 3, 4]; // Assuming these are valid team ids in your teams table
        $shiftIds = [1, 2, 3]; // Assuming these are valid shift ids in your shifts table

        foreach ($users as $user) {
            if(!User::find($user->id)->hasRole('Super Admin')){
            JobInfo::create([
                'user_id' => $user->id,
                'designation_id' => $designationIds[array_rand($designationIds)],
                'report_to' => User::where('id', '<>', $user->id)->inRandomOrder()->first()->id, // select other user as manager
                'team_id' => $teamIds[array_rand($teamIds)],
                'shift_id' => $shiftIds[array_rand($shiftIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        else{
            JobInfo::create([
                'user_id' => $user->id,
                'designation_id' => null,
                'report_to' => null, // select other user as manager
                'team_id' => $teamIds[array_rand($teamIds)],
                'shift_id' => $shiftIds[array_rand($shiftIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        }
    }
}
