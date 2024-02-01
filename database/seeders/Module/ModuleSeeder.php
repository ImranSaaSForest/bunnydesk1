<?php

namespace Database\Seeders\Module;

use App\Models\Module\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules=[
            [
                'name' =>'employeedirectory',
               
            ],

            [
                'name' =>'timeoff',
               
            ],

            [
                'name' =>'timesheet',
               
            ],
            [
                'name' =>'hR tools',
               
            ],
            [
                'name' =>'helpdesk',
               
            ],
            [
                'name' =>'recruitment',
               
            ],
            [
                'name' =>'lms',
               
            ],
            [
                'name' =>'insights',
               
            ],
            [
                'name' =>'attendance',
               
            ],
            [
                'name' =>'finance',
               
            ],
            [
                'name' =>'forum',
               
            ],
            [
                'name' =>'settings',
               
            ],
            
            

        ];
        foreach ($modules as $module){
            Module::create($module);
        }
    }
}
