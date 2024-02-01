<?php

namespace Database\Seeders\Jobs;

use App\Models\Recruitment\JobAdditional;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class job_application extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application=[
            [
                'name' => 'Desired Salary'
            ],
            [
                'name' => 'LinkedIn URL'
            ],
            [
                'name' => 'Referred By (Employee ID)'
            ],
            [
                'name' => 'Website, Blog or Portfolio'
            ],
            [
                'name' => 'References'
            ],
            [
                'name' => 'Resume'
            ],
            [
                'name' => 'Cover Letter'
            ],
            [
                'name' => 'Address'
            ],
            [
                'name' => 'Education Details'
            ],
            [
                'name' => 'Visa Validation'
            ],
            // Address, College/University, Visa Validation

        ];
        foreach($application as $applications){
            JobAdditional::create($applications);
        }
    }
}
