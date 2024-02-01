<?php

namespace Database\Seeders\Employee;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Asset\Asset;
use App\Models\Asset\EmployeeAsset;

class AssetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assuming you have a Company with id=1
        $company_id = 1;

        $assets = [
            [
                'company_id' => $company_id,
                'name' => 'Desktop Computer',
                'description' => 'Apple iMac 2020',
                'value' => 1500.00,
                'purchase_date' => '2020-01-01',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Office Chair',
                'description' => 'Ergonomic office chair',
                'value' => 300.00,
                'purchase_date' => '2021-01-01',
            ],
            // add more assets as needed
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }

        $users = User::all();

        foreach ($users as $user) {
            EmployeeAsset::create([
                'user_id' => $user->id,
                'asset_id' => Asset::all()->random()->id,
            ]);
        }
    }
}
