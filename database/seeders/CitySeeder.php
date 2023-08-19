<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json_cities = file_get_contents(storage_path('app/cities.json'));

        $cities = json_decode($json_cities, true);

        foreach ($cities as $city) {
            City::create([
                'code' => $city['id'],
                'name' => $city['lokasi'],
            ]);
        }
    }
}
