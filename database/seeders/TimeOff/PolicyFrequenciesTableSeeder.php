<?php

namespace Database\Seeders\TimeOff;

use Illuminate\Database\Seeder;
use App\Models\TimeOff\PolicyFrequency;

class PolicyFrequenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frequencies = [
            ['name' => 'Annually'],
            ['name' => 'Monthly'],
            ['name' => 'Weekly'],
            ['name' => 'Daily'],
        ];

        foreach ($frequencies as $frequency) {
            PolicyFrequency::create($frequency);
        }
    }
}
