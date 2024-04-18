<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $tenant = DB::table('tenants')->first();

        foreach (range(1, 15) as $index) {
            DB::table('blog_categories')->insert([
                'name' => $faker->word,
                'slug' => $faker->unique()->slug,
                'description' => $faker->optional()->sentence($nbWords = 6, $variableNbWords = true), // Sometimes null
                'is_visible' => $faker->boolean($chanceOfGettingTrue = 50),
                'seo_title' => $faker->optional()->text($maxNbChars = 60),
                'seo_description' => $faker->optional()->text($maxNbChars = 160),
                'created_at' => now(),
                'updated_at' => now(),
                'tenant_id' => $tenant->id,
            ]);

        }
    }
}
