<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AnnouncementSeeder::class,
            BannerSeeder::class,
            SettingSeeder::class,
            CitySeeder::class,
            BranchSeeder::class,
            UserSeeder::class,
            JobPositionSeeder::class,
            EmployeeSeeder::class
        ]);
    }
}
