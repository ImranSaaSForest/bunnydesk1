<?php

namespace Database\Seeders\Attendance;

use App\Models\Attendance\AttendanceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types=[
            [
                'name' =>'work',
                'description'=>'working hour'
            ],
            [
                'name' =>'morning break',
                'description'=>'paid break entry'
            ],
            [
                'name' =>'lunch',
                'description'=>'paid break entry'
            ],
            [
                'name' =>'evening break',
                'description'=>'paid break entry'
            ],
            [
                'name' =>'unpaid break',
                'description'=>'unpaid break entry'
            ]

        ];
        foreach ($types as $type){
            AttendanceType::create($type);
        }
    }
}
