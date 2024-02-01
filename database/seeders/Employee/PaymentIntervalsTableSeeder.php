<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentIntervalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $intervals = ['Hourly', 'Daily', 'Weekly', 'Biweekly', 'Monthly', 'Yearly'];

        foreach ($intervals as $interval) {
            DB::table('payment_intervals')->insert([
                'name' => $interval,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
