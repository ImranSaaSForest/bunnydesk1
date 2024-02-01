<?php

namespace Database\Seeders\Jobs;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Developers',
                'job_status' => 'approved',
                'hiring_lead_id' => 1,
                'onboarding_list_id' => 1,
                'employee_type_id'=> 1,
                'designation_id'=> 1,
                'description'=>'This job is for Developers',
                'city'=>'Madurai',
                'province'=>'Tamilnadu',
                'postal_code'=>'625001',
                'country'=>'India',
                'salary'=> '4000',
                'company_id'=> 1,
                'interview_date'=> now(),
                'created_by'=> 1,
                'updated_by'=> 1,
                'approved_by'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'title' => 'HR Manager',
                'job_status' => 'approved',
                'hiring_lead_id' => 1,
                'onboarding_list_id' => 1,
                'employee_type_id'=> 2,
                'designation_id'=> 3,
                'description'=>'This job is for HR Manager',
                'city'=>'Madurai',
                'province'=>'Tamilnadu',
                'postal_code'=>'625001',
                'country'=>'India',
                'salary'=> '4000',
                'company_id'=> 1,
                'interview_date'=> now(),
                'created_by'=> 1,
                'updated_by'=> 1,
                'approved_by'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'title' => 'Senior Manager',
                'job_status' => 'approved',
                'hiring_lead_id' => 1,
                'onboarding_list_id' => 1,
                'employee_type_id'=> 3,
                'designation_id'=> 4,
                'description'=>'This job is for Senior Manager',
                'city'=>'Madurai',
                'province'=>'Tamilnadu',
                'postal_code'=>'625001',
                'country'=>'India',
                'salary'=> '4000',
                'company_id'=> 1,
                'interview_date'=> now(),
                'created_by'=> 1,
                'updated_by'=> 1,
                'approved_by'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'title' => 'Digital Marketing',
                'job_status' => 'approved',
                'hiring_lead_id' => 1,
                'onboarding_list_id' => 1,
                'employee_type_id'=> 4,
                'designation_id'=> 2,
                'description'=>'This job is for Digital Marketing',
                'city'=>'Madurai',
                'province'=>'Tamilnadu',
                'postal_code'=>'625001',
                'country'=>'India',
                'salary'=> '4000',
                'company_id'=> 2,
                'interview_date'=> now(),
                'created_by'=> 1,
                'updated_by'=> 1,
                'approved_by'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'title' => 'Quality Analyst',
                'job_status' => 'approved',
                'hiring_lead_id' => 1,
                'employee_type_id'=> 1,
                'onboarding_list_id' => 1,
                'designation_id'=> 4,
                'description'=>'This job is for Quality Analyst',
                'city'=>'Madurai',
                'province'=>'Tamilnadu',
                'postal_code'=>'625001',
                'country'=>'India',
                'salary'=> '4000',
                'company_id'=> 3,
                'interview_date'=> now(),
                'created_by'=> 1,
                'updated_by'=> 1,
                'approved_by'=>1,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]
            ];

            foreach ($jobs as $job) {
                DB::table('jobs')->insert($job);
            }
    }
}
