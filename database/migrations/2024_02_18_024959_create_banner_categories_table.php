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
        Schema::create('banner_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('parent_id')->nullable(); // Self-referencing foreign key
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('banner_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_categories');
    }
};
