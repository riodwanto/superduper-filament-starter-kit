<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BannersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 25; $i++) {
            DB::table('banners')->insert([
                'id' => $faker->uuid(),
                'category' => $faker->word,
                'sort' => $i,
                'title' => $faker->sentence,
                'description' => $faker->text(200),
                'image_url' => $faker->imageUrl(640, 480, 'business', true),
                'is_active' => $faker->boolean(70), // 70% chance of being true
                'start_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'end_date' => $faker->dateTimeBetween('+1 month', '+2 months'),
                'click_url' => $faker->url,
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
