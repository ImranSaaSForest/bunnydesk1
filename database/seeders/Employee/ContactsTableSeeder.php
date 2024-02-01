<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee\Contact;

class ContactsTableSeeder extends Seeder
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

        foreach ($users as $user) {
            Contact::create([
                'user_id' => $user->id,
                'work_phone' => '123-456-7890',
                'mobile_phone' => '+14845551234',
                'home_phone' => '111-222-3333',
                'home_email' => $user->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
