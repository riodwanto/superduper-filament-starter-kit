<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Blog\Category;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use Database\Factories\HtmlProvider;

class BlogPostsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $faker->addProvider(new HtmlProvider($faker));

        $authorIds = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'author');
        })->pluck('id')->toArray();

        $categoryIds = Category::pluck('id')->toArray();

        foreach (range(1, 12) as $index) {
            $title = $faker->sentence;
            $content = $faker->randomHtml(); // Generate HTML content

            DB::table('blog_posts')->insert([
                'id' => (string) new Ulid(),
                'blog_author_id' => $faker->randomElement($authorIds),
                'blog_category_id' => $faker->randomElement($categoryIds),
                'is_featured' => $faker->boolean(30),
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => $content,
                'content_overview' => Str::limit(strip_tags($content), 150),
                'published_at' => $faker->optional()->dateTimeBetween('-1 year', 'now'),
                'seo_title' => $faker->optional()->text(60),
                'seo_description' => $faker->optional()->text(160),
                'image' => $faker->optional()->imageUrl(640, 480),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
