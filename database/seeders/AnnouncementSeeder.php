<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        for ($i = 0; $i < 30; $i++) {
            $title = $faker->sentence(mt_rand(4, 8));
            $slug = Str::slug($title);
            Announcement::create([
                'title' => $title,
                'slug'  => $slug,
                'content' => $faker->paragraph(mt_rand(5, 10))
            ]);
        }
    }
}
