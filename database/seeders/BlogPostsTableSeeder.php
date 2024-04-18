<?php

namespace Database\Seeders;

use App\Models\Blog\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;

class BlogPostsTableSeeder extends Seeder
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

        $authorIds = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'author');
        })->pluck('id')->toArray();

        $categoryIds = Category::pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            DB::table('blog_posts')->insert([
                'blog_author_id' => $faker->randomElement($authorIds),
                'blog_category_id' => $faker->randomElement($categoryIds),
                'title' => $faker->sentence,
                'slug' => $faker->unique()->slug,
                'content' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
                'published_at' => $faker->optional()->date,
                'seo_title' => $faker->optional()->text($maxNbChars = 60),
                'seo_description' => $faker->optional()->text($maxNbChars = 160),
                'image' => $faker->optional()->imageUrl($width = 640, $height = 480),
                'created_at' => now(),
                'updated_at' => now(),
                'tenant_id' => $tenant->id,
            ]);
        }
    }
}
