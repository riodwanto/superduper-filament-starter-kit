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
        Schema::create('banner_contents', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('banner_category_id')->nullable()->index()->nullOnDelete();
            $table->smallInteger('sort')->default(0)->index();
            $table->boolean('is_active')->default(false)->index();

            $table->string('title', 255)->nullable()->index();
            $table->string('description', 500)->nullable();
            $table->string('click_url', 255)->nullable();
            $table->string('click_url_target', 20)->default('_self')->nullable();
            $table->dateTime('start_date')->nullable()->index();
            $table->dateTime('end_date')->nullable()->index();
            $table->dateTime('published_at')->nullable()->index();
            $table->string('locale', 10)->default('en')->index();
            $table->json('options')->nullable();

            $table->unsignedBigInteger('impression_count')->default(0);
            $table->unsignedBigInteger('click_count')->default(0);

            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_contents');
    }
};
