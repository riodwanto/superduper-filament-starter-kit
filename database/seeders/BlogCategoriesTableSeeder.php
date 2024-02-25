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

        $num = 15;

        $progressBar = $this->command->getOutput()->createProgressBar($num);
        $progressBar->start();

        foreach (range(1, $num) as $index) { // Seed 10 categories, adjust the number as needed
            DB::table('blog_categories')->insert([
                'name' => $faker->word,
                'slug' => $faker->unique()->slug,
                'description' => $faker->optional()->sentence($nbWords = 6, $variableNbWords = true), // Sometimes null
                'is_visible' => $faker->boolean($chanceOfGettingTrue = 50),
                'seo_title' => $faker->optional()->text($maxNbChars = 60),
                'seo_description' => $faker->optional()->text($maxNbChars = 160),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->info('blog_categories table seeded!');
    }
}
