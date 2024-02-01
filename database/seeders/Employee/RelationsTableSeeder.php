<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relations = ['Spouse', 'Parent', 'Sibling', 'Child', 'Friend', 'Other'];

        foreach ($relations as $relation) {
            DB::table('relations')->insert([
                'name' => $relation,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
