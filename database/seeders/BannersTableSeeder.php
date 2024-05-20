<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;

class BannersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            $name = $faker->words(3, true);
            DB::table('banner_categories')->insert([
                'id' => (string) new Ulid(),
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->text(200),
                'is_active' => $faker->boolean(70),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear()
            ]);
        }

        $bannerCategoryIds = DB::table('banner_categories')->pluck('id')->all();

        for ($i = 0; $i < 12; $i++) {
            DB::table('banners')->insert([
                'id' => (string) new Ulid(),
                'banner_category_id' => $faker->randomElement($bannerCategoryIds),
                'sort' => $i,
                'title' => $faker->sentence,
                'description' => $faker->text(150),
                'image_url' => $faker->imageUrl(640, 480, 'business', true),
                'is_visible' => $faker->boolean(65), // 70% chance of being true
                'start_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'end_date' => $faker->dateTimeBetween('+1 month', '+2 months'),
                'click_url' => $faker->url,
                'click_url_target' => $faker->randomElement(['_blank', '_self', '_parent', '_top']),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
