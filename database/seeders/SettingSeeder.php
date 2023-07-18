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
            'office_name'           => 'Reed co.',
            'office_logo'           => null,
            'presence_entry_start'  => '06:00:00',
            'presence_entry_end'    => '08:00:00',
            'presence_exit'         => '17:00:00',
            'presence_meter_radius' => 12,
            'mobile_app_version'    => '1.0.0',
            'play_store_url'        => 'https://play.google.com',
        ]);
    }
}
