<?php

namespace Database\Seeders;

use App\Models\JobPosition;
use Faker\Factory;
use Illuminate\Database\Seeder;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        for ($i = 0; $i < 5; $i++) {
            JobPosition::create([
                'name' => $faker->jobTitle(),
                'code' => $faker->bothify('?????-####'),
            ]);
        }
    }
}
