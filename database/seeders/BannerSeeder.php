<?php

namespace Database\Seeders;

use App\Models\Banner;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        for ($i = 0; $i < 30; $i++) {
            Banner::create([
                'name' => $faker->words(mt_rand(1, 3), true),
                'image' => $faker->bothify('?????-#####') . '.png'
            ]);
        }
    }
}
