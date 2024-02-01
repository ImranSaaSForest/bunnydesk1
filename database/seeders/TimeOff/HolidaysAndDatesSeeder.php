<?php

namespace Database\Seeders\TimeOff;

use Illuminate\Database\Seeder;
use App\Models\TimeOff\Holiday;
use App\Models\TimeOff\HolidayDate;

class HolidaysAndDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Holiday data to be seeded
        $holidays = [
            // US holidays
            [
                'name' => 'US Holidays',
                'description' => 'Common holidays in the United States',
                'is_active' => true,
                'dates' => [
                    [
                        'name' => 'New Year 2024',
                        'holiday_date' => '2024-01-01',
                        'description' => 'New Year Day',
                        'optional' => false
                    ],
                    [
                        'name' => 'Independence Day 2024',
                        'holiday_date' => '2024-07-04',
                        'description' => 'Independence Day',
                        'optional' => false
                    ],
                    [
                        'name' => 'Thanksgiving 2024',
                        'holiday_date' => '2024-11-28',
                        'description' => 'Thanksgiving Day',
                        'optional' => false
                    ],
                    [
                        'name' => 'Christmas 2024',
                        'holiday_date' => '2024-12-25',
                        'description' => 'Christmas Day',
                        'optional' => false
                    ],
                ]
            ],

            // Indian holidays
            [
                'name' => 'India Holidays',
                'description' => 'Common holidays in India',
                'is_active' => true,
                'dates' => [
                    [
                        'name' => 'Republic Day 2024',
                        'holiday_date' => '2024-01-26',
                        'description' => 'Republic Day',
                        'optional' => false
                    ],
                    [
                        'name' => 'Independence Day 2024',
                        'holiday_date' => '2024-08-15',
                        'description' => 'Independence Day',
                        'optional' => false
                    ],
                    [
                        'name' => 'Gandhi Jayanti 2024',
                        'holiday_date' => '2024-10-02',
                        'description' => 'Gandhi Jayanti',
                        'optional' => false
                    ],
                    [
                        'name' => 'Diwali 2024',
                        'holiday_date' => '2024-11-02',
                        'description' => 'Diwali',
                        'optional' => false
                    ],
                ]
            ],
        ];

        // Create holidays and their respective dates
        foreach ($holidays as $holidayData) {
            $holiday = Holiday::create([
                'name' => $holidayData['name'],
                'description' => $holidayData['description'],
                'is_active' => $holidayData['is_active'],
            ]);

            foreach ($holidayData['dates'] as $date) {
                HolidayDate::create([
                    'name' => $date['name'],
                    'holiday_id' => $holiday->id,
                    'holiday_date' => $date['holiday_date'],
                    'description' => $date['description'],
                    'optional' => $date['optional'],
                ]);
            }
        }
    }
}
