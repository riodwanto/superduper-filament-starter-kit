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

        // Create parent categories
        $parentCategoryIds = [];
        for ($i = 0; $i < 5; $i++) {
            $name = $faker->words(3, true);
            $id = (string) new Ulid();
            $parentCategoryIds[] = $id;

            DB::table('banner_categories')->insert([
                'id' => $id,
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->text(200),
                'is_active' => $faker->boolean(70),
                'meta_title' => $faker->sentence(4),
                'meta_description' => $faker->sentence(10),
                'locale' => $faker->randomElement(['en', 'id', 'zh', 'ja']),
                'options' => json_encode(['position' => $faker->randomElement(['top', 'side', 'bottom'])]),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear()
            ]);
        }

        // Create some child categories
        $allCategoryIds = $parentCategoryIds;
        for ($i = 0; $i < 5; $i++) {
            $name = $faker->words(3, true);
            $id = (string) new Ulid();
            $allCategoryIds[] = $id;

            DB::table('banner_categories')->insert([
                'id' => $id,
                'parent_id' => $faker->randomElement($parentCategoryIds),
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->text(200),
                'is_active' => $faker->boolean(70),
                'meta_title' => $faker->sentence(4),
                'meta_description' => $faker->sentence(10),
                'locale' => $faker->randomElement(['en', 'id', 'zh', 'ja']),
                'options' => json_encode(['position' => $faker->randomElement(['top', 'side', 'bottom'])]),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear()
            ]);
        }

        // Create banner contents
        for ($i = 0; $i < 20; $i++) {
            $startDate = $faker->dateTimeBetween('-1 month', '+1 month');
            $endDate = $faker->dateTimeBetween($startDate, '+3 months');

            DB::table('banner_contents')->insert([
                'id' => (string) new Ulid(),
                'banner_category_id' => $faker->randomElement($allCategoryIds),
                'sort' => $i,
                'title' => $faker->sentence,
                'description' => $faker->text(150),
                'is_active' => $faker->boolean(70),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'published_at' => $faker->dateTimeBetween('-2 months', 'now'),
                'click_url' => $faker->url,
                'click_url_target' => $faker->randomElement(['_blank', '_self']),
                'locale' => $faker->randomElement(['en', 'id', 'zh', 'ja']),
                'options' => json_encode([
                    'display_type' => $faker->randomElement(['slide', 'static', 'popup']),
                    'transition' => $faker->randomElement(['fade', 'slide', 'none']),
                    'priority' => $faker->numberBetween(1, 10)
                ]),
                'impression_count' => $faker->numberBetween(100, 10000),
                'click_count' => $faker->numberBetween(10, 1000),
                'created_at' => $faker->dateTimeThisYear(),
                'updated_at' => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
