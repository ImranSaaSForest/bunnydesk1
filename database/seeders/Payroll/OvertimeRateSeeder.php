<?php

namespace Database\Seeders\Payroll;

use App\Models\Payroll\OverTimeRate;
use App\Models\Payroll\WorkTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OvertimeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $overTime=OverTimeRate::create([
        'name'=>'Liberian Overtime',
        'percentage'=>50,
       ]);

       $workTime=WorkTime::create([
        'over_time_rate_id'=>$overTime->id,
        'per'=>'day',
        'allowed_hour'=>8,
       ]);
    }
}
