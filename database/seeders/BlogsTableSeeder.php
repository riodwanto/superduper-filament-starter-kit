<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Database\Factories\HtmlProvider;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedCategories();
        $this->seedPosts();
    }

    /**
     * Seed blog categories
     */
    private function seedCategories()
    {
        $faker = Faker::create();

        // Get admin users for creator/updater fields
        $adminIds = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->pluck('id')->toArray();

        // If no admins found, fallback to any users
        if (empty($adminIds)) {
            $adminIds = User::pluck('id')->toArray();
        }

        if (empty($adminIds)) {
            // Create a default user if none exists
            $userId = (string) new Ulid();
            User::create([
                'id' => $userId,
                'firstname' => 'System',
                'lastname' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            $adminIds = [$userId];
        }

        // Create parent categories
        $parentCategories = [];
        foreach (range(1, 4) as $index) {
            $name = ucwords($faker->words(2, true));
            $creatorId = $faker->randomElement($adminIds);
            $parentCategories[] = [
                'id' => (string) new Ulid(),
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->paragraph(2),
                'is_active' => true,
                'meta_title' => $name,
                'meta_description' => $faker->sentence(10),
                'locale' => 'en',
                'options' => json_encode(['order' => $index]),
                'created_by' => $creatorId,
                'updated_by' => $creatorId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('blog_categories')->insert($parentCategories);

        // Create child categories
        $parentIds = DB::table('blog_categories')->pluck('id')->toArray();

        $childCategories = [];
        foreach (range(1, 8) as $index) {
            $name = ucwords($faker->words(3, true));
            $creatorId = $faker->randomElement($adminIds);
            $updaterId = $faker->boolean(30) ? $faker->randomElement($adminIds) : $creatorId;

            $childCategories[] = [
                'id' => (string) new Ulid(),
                'parent_id' => $faker->randomElement($parentIds),
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $faker->paragraph(2),
                'is_active' => $faker->boolean(70),
                'meta_title' => $name,
                'meta_description' => $faker->sentence(10),
                'locale' => 'en',
                'options' => json_encode(['order' => $index]),
                'created_by' => $creatorId,
                'updated_by' => $updaterId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('blog_categories')->insert($childCategories);
    }

    /**
     * Seed blog posts
     */
    private function seedPosts()
    {
        $faker = Faker::create();

        // Add HTML provider if you have it
        if (class_exists('Database\Factories\HtmlProvider')) {
            $faker->addProvider(new HtmlProvider($faker));
        }

        // Get authors (users with author role)
        $authorIds = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'author');
        })->pluck('id')->toArray();

        // If no authors found, fallback to any users
        if (empty($authorIds)) {
            $authorIds = User::pluck('id')->toArray();
        }

        // Get editors/admins for created_by/updated_by fields
        $editorIds = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['editor', 'admin']);
        })->pluck('id')->toArray();

        // If no editors found, use the same users as authors
        if (empty($editorIds)) {
            $editorIds = $authorIds;
        }

        // Get categories
        $categoryIds = Category::pluck('id')->toArray();

        // Prepare posts data
        $posts = [];
        foreach (range(1, 20) as $index) {
            $title = $faker->sentence;
            $creatorId = $faker->randomElement($editorIds);
            $updaterId = $faker->boolean(40) ? $faker->randomElement($editorIds) : $creatorId;

            // Generate Markdown content instead of HTML
            $contentRaw = "# " . $faker->sentence . "\n\n";
            $contentRaw .= $faker->paragraph(3) . "\n\n";
            $contentRaw .= "## " . $faker->sentence . "\n\n";
            $contentRaw .= $faker->paragraph(4) . "\n\n";
            $contentRaw .= "* " . $faker->sentence . "\n";
            $contentRaw .= "* " . $faker->sentence . "\n";
            $contentRaw .= "* " . $faker->sentence . "\n\n";
            $contentRaw .= "## " . $faker->sentence . "\n\n";
            $contentRaw .= $faker->paragraph(3) . "\n\n";
            $contentRaw .= "> " . $faker->sentence . "\n\n";
            $contentRaw .= $faker->paragraph(2);

            // Convert Markdown to HTML using CommonMark
            $converter = new \League\CommonMark\GithubFlavoredMarkdownConverter([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);

            $contentHtml = $converter->convert($contentRaw)->getContent();

            // Generate content overview
            $contentOverview = Str::limit(strip_tags($contentHtml), 150);

            // Calculate reading time (approx. 200 words per minute)
            $wordCount = str_word_count(strip_tags($contentHtml));
            $readingTime = ceil($wordCount / 200);

            // Determine status and dates
            $status = $faker->randomElement(['draft', 'pending', 'published', 'archived']);
            $publishedAt = null;
            $lastPublishedAt = null;
            $createdAt = $faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now')->format('Y-m-d H:i:s');

            if ($status === 'published') {
                $publishedAt = $faker->dateTimeBetween($createdAt, $updatedAt)->format('Y-m-d');
                $lastPublishedAt = $faker->dateTimeBetween($publishedAt, $updatedAt)->format('Y-m-d H:i:s');
            }

            $posts[] = [
                'id' => (string) new Ulid(),
                'blog_author_id' => $faker->randomElement($authorIds),
                'blog_category_id' => $faker->randomElement($categoryIds),
                'title' => $title,
                'slug' => Str::slug($title),
                'content_raw' => $contentRaw,
                'content_html' => $contentHtml,
                'content_overview' => $contentOverview,
                'is_featured' => $faker->boolean(20),
                'status' => $status,
                'published_at' => $publishedAt,
                'last_published_at' => $lastPublishedAt,
                'scheduled_at' => null,
                'meta_title' => substr($title, 0, 60),
                'meta_description' => $contentOverview,
                'locale' => 'en',
                'options' => json_encode(['show_author' => true, 'show_date' => true]),
                'view_count' => $faker->numberBetween(0, 1000),
                'comments_count' => $faker->numberBetween(0, 50),
                'reading_time' => $readingTime,
                'created_by' => $creatorId,
                'updated_by' => $updaterId,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        // Insert posts
        foreach (array_chunk($posts, 5) as $chunk) {
            DB::table('blog_posts')->insert($chunk);
        }
    }
}
