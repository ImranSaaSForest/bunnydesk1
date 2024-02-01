<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee\Benefit;
use App\Models\Employee\EmployeeBenefit;

class BenefitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefits = [
            [
                'name' => 'Health Insurance',
                'description' => 'Company-paid health insurance.',
                'currency' => 'USD',
                'amount' => 5000,
            ],
            [
                'name' => '401(k) Matching',
                'description' => 'Company matches contributions to 401(k).',
                'currency' => 'USD',
                'amount' => 2000,
            ],
            // add more benefits as needed
        ];

        foreach ($benefits as $benefit) {
            Benefit::create($benefit);
        }

        $users = User::all();

        foreach ($users as $user) {
            EmployeeBenefit::create([
                'user_id' => $user->id,
                'benefit_id' => Benefit::all()->random()->id,
            ]);
        }
    }
}
