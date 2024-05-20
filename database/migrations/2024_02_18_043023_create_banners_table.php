<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('banner_category_id')->nullable()->nullOnDelete();
            $table->smallInteger('sort')->default(0)->index();
            $table->boolean('is_visible')->default(false)->index();
            $table->string('title', 255)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->string('click_url', 255)->nullable();
            $table->string('click_url_target', 20)->default('_self')->nullable();
            $table->dateTime('start_date')->nullable()->index();
            $table->dateTime('end_date')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
