<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            ['department_id' => 1, 'name' => 'Product Manager'],
            ['department_id' => 1, 'name' => 'Assistant Manager'],
            ['department_id' => 2, 'name' => 'Senior Engineer'],
            ['department_id' => 2, 'name' => 'Junior Engineer'],
            ['department_id' => 3, 'name' => 'Marketing Specialist'],
            ['department_id' => 3, 'name' => 'Marketing Assistant'],
            ['department_id' => 4, 'name' => 'Sales Executive'],
            ['department_id' => 4, 'name' => 'Sales Assistant'],
        ];

        foreach ($designations as $designation) {
            DB::table('designations')->insert([
                'department_id' => $designation['department_id'],
                'name' => $designation['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
