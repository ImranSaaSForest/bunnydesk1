<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee\SalaryDetail;

class SalaryDetailsTableSeeder extends Seeder
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
        $paymentIntervalIds = [1, 2, 3]; // Assuming these are valid payment interval ids in your payment_intervals table
        $paymentMethodIds = [1, 2, 3, 4]; // Assuming these are valid payment method ids in your payment_methods table

        foreach ($users as $user) {
            SalaryDetail::create([
                'user_id' => $user->id,
                'amount' => 50000.00,
                'currency' => 'LRD',

                'payment_interval_id' => $paymentIntervalIds[array_rand($paymentIntervalIds)],
                'payment_method_id' => $paymentMethodIds[array_rand($paymentMethodIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
