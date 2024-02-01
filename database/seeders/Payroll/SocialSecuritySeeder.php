<?php

namespace Database\Seeders\Payroll;

use App\Models\Payroll\SocialSecurity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialSecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialSecurity= SocialSecurity::create([
            'name'=>'Liberian Social Security',
            'employee_contribution'=>4,
            'employer_contribution'=>6,
            'description'=>'Liberian Social Security',
          ]);
    }
}
