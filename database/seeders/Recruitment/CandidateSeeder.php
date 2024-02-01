<?php

namespace Database\Seeders\Recruitment;

use Carbon\Carbon;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [
            [
                'company_id' => 1,
                'job_id' => 1,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'images/6LJVRu7k5LsHsu0kzfGGhhRB4cWz8vFJ31VI922W.jpg',
                'job_accept_code' => 'ABC1230',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'job_id' => 2,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'images/6LJVRu7k5LsHsu0kzfGGhhRB4cWz8vFJ31VI922W.jpg',
                'job_accept_code' => 'ABC1239',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'job_id' => 2,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC1238',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'job_id' => 3,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC1237',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'job_id' => 3,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC1236',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => Carbon::create('2023-09-20 11:55:31'),
                'updated_at' => Carbon::create('2023-09-20 11:55:31'),
            ],
            [
                'company_id' => 1,
                'job_id' => 3,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC1235',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 1,
                'job_id' => 4,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC1234',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => Carbon::create('2023-10-20 11:55:31'),
                'updated_at' => Carbon::create('2023-10-20 11:55:31'),
            ],
            [
                'company_id' => 2,
                'job_id' => 4,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC123',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => Carbon::create('2023-10-20 11:55:31'),
                'updated_at' => Carbon::create('2023-10-20 11:55:31'),
            ],
            [
                'company_id' => 2,
                'job_id' => 4,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC12',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => Carbon::create('2023-08-20 11:55:31'),
                'updated_at' => Carbon::create('2023-08-20 11:55:31'),
            ],
            [
                'company_id' => 3,
                'job_id' => 4,
                'status' => 'applied',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'pnone_number' => 1234567890,
                'photo' => 'image1.jpg',
                'job_accept_code' => 'ABC1',
                'rating' => 2,
                'offer_sent_by' => null,
                'offer_sent_on' => null,
                'created_at' => Carbon::create('2023-07-20 11:55:31'),
                'updated_at' => Carbon::create('2023-07-20 11:55:31'),
            ],
            // Add more sample data as needed
        ];

        // Insert data into the applications table
        foreach ($applications as $job) {
            DB::table('candidates')->insert($job);
        }
    }
}
