<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUuid('blog_author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUlid('blog_category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('title');
            $table->string('slug');

            // Content Fields
            $table->longText('content_raw');
            $table->longText('content_html');
            $table->text('content_overview')->nullable();

            $table->enum('status', ['draft', 'pending', 'published', 'archived'])->default('draft')->index();
            $table->date('published_at')->nullable()->index();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('last_published_at')->nullable()->index();
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('locale', 10)->default('en')->index();
            $table->json('options')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('reading_time')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['slug', 'locale']); // Unique constraint for slug and locale combination

            if (config('database.default') === 'mysql') {
                $table->fullText(['title', 'content_overview']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
