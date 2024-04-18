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
        // Banner
        Schema::table('banners', function (Blueprint $table) {
            $table->foreignUlid('tenant_id')->nullable()->constrained();
        });

        // Blog
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->foreignUlid('tenant_id')->nullable()->constrained();
        });
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreignUlid('tenant_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign('tenant_id');
        });
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropForeign('tenant_id');
        });
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign('tenant_id');
        });
    }
};
