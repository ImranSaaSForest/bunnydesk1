<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee\BankInfo;

class BankInfosTableSeeder extends Seeder
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
            BankInfo::create([
                'user_id' => $user->id,
                'bank_name' => 'Bank of ' . $user->name,
                'name' => $user->name,
                'ifsc' => 'BANK' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'micr' => 'MICR' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'account_number' => 'AC' . str_pad($user->id, 10, '0', STR_PAD_LEFT),
                'branch_code' => 'BC' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
