<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'John', 'last_name' => 'Doe', 'email' => 'john@doe.com', 'password' => 'password'],
            ['name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@doe.com', 'password' => 'password'],
            ['name' => 'Michael', 'last_name' => 'Smith', 'email' => 'michael@smith.com', 'password' => 'password'],
            ['name' => 'Emma', 'last_name' => 'Johnson', 'email' => 'emma@johnson.com', 'password' => 'password'],
            ['name' => 'Daniel', 'last_name' => 'Brown', 'email' => 'daniel@brown.com', 'password' => 'password'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
