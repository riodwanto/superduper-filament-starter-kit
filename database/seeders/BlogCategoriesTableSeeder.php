<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
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

        foreach (range(1, 6) as $index) {
            $name = $faker->words(3, true);
            DB::table('blog_categories')->insert([
                'id' => (string) new Ulid(),
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->optional()->sentence($nbWords = 6, $variableNbWords = true),
                'is_active' => $faker->boolean(70),
                'seo_title' => $faker->optional()->text($maxNbChars = 60),
                'seo_description' => $faker->optional()->text($maxNbChars = 160),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }
}
