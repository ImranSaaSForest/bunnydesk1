<?php

namespace Database\Seeders\Recruitment;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateAdditionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = [
            [
                'candidate_id'=>1,
                'resume_path' => 'resume/Wzd8qYRoAU80BnbWjwcEpnFfW2tOtPpXHlTr1p0t.pdf',
                'desired_salary' => '$80,000',
                'cover_letter' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'linkedin_url' => 'https://www.linkedin.com/in/johndoe/',
                'website_blog_portfolio' => 'https://www.johndoe.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'candidate_id'=>2,
                'resume_path' => 'resume/0kHvDdsLbYlFfEPABliTsZzugocBoAeSiGt4xZtV.pdf',
                'desired_salary' => '$70,000',
                'cover_letter' => ' consectetur adipiscing elit.',
                'linkedin_url' => 'https://www.linkedin.com/in/johndoe/',
                'website_blog_portfolio' => 'https://www.johndoe.com/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more sample data as needed
        ];
         // Insert data into the applications table
         foreach ($candidates as $job) {
            DB::table('candidate_additionals')->insert($job);
        }
    }
}
