<?php

namespace Database\Seeders;

use App\Models\CustomAttendanceLocation;
use App\Models\Employee;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CustomAttendanceLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        CustomAttendanceLocation::create([
            'employee_uuid' => Employee::inRandomOrder()->first()->uuid,
            'start_date' => date("Y-m-d H:i:s", strtotime("1 day")),
            'end_date' => $faker->dateTimeBetween('now', '2023-07-31')->format('Y-m-d'),
            'presence_location_address'   => $faker->address(),
            'presence_location_latitude' => $faker->latitude(),
            'presence_location_longitude' => $faker->longitude(),
        ]);
    }
}
