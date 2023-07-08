<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'office_name'                 => 'Reed co.',
            'office_logo'                 => null,
            'presence_entry_start'        => '06:00:00',
            'presence_entry_end'          => '08:00:00',
            'presence_exit'               => '17:00:00',
            'presence_location_address'   => 'Sumampir RT 10/03',
            'presence_location_latitude'  => '41.40338',
            'presence_location_longitude' => '2.17403',
            'presence_meter_radius'       => 12,
        ]);
    }
}
