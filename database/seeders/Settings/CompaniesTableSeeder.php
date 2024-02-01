<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;
use App\Models\Company\Company;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'name' => 'Tech Corp',
                'logo' => null,
                'company_type' => 'Technology',
                'tax_id' => 'TAX123456',
                'registration_id' => 'REG123456',
                'currency' => 'LRD',
                'latitude' => '37.7749',
                'longitude' => '-122.4194',
                'timezone' => 'America/Los_Angeles',
                'phone' => '1234567890',
                'mobile' => '+14845551234',
                'email' => 'info@techcorp.com',
                'website' => 'www.techcorp.com',
                'street' => '123 Tech Street',
                'street2' => 'Suite 456',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '94103',
                'country' => 'LR',
                'twitter' => 'techcorp',
                'facebook' => 'techcorp',
                'youtube' => 'techcorp',
                'instagram' => 'techcorp',
            ],
            [
                'name' => 'SaaS',
                'logo' => null,
                'company_type' => 'Technology',
                'tax_id' => 'TAX123456',
                'registration_id' => 'REG123456',
                'currency' => 'USD',
                'latitude' => '37.7749',
                'longitude' => '-122.4194',
                'timezone' => 'America/Los_Angeles',
                'phone' => '1234567890',
                'mobile' => '+14845551234',
                'email' => 'saas@saasforest.com',
                'website' => 'www.saas.com',
                'street' => '123 Tech Street',
                'street2' => 'Suite 456',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '94103',
                'country' => 'USA',
                'twitter' => 'techcorp',
                'facebook' => 'techcorp',
                'youtube' => 'techcorp',
                'instagram' => 'techcorp',
            ],
            [
                'name' => 'Forest',
                'logo' => null,
                'company_type' => 'Technology',
                'tax_id' => 'TAX123456',
                'registration_id' => 'REG123456',
                'currency' => 'USD',
                'latitude' => '37.7749',
                'longitude' => '-122.4194',
                'timezone' => 'America/Los_Angeles',
                'phone' => '1234567890',
                'mobile' => '+14845551234',
                'email' => 'forest@saasforest.com',
                'website' => 'www.forest.com',
                'street' => '123 Tech Street',
                'street2' => 'Suite 456',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '94103',
                'country' => 'USA',
                'twitter' => 'techcorp',
                'facebook' => 'techcorp',
                'youtube' => 'techcorp',
                'instagram' => 'techcorp',
            ]
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
