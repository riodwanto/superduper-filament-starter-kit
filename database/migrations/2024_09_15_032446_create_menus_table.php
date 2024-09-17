<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Datlechin\FilamentMenuBuilder\Enums\LinkTarget;
use Datlechin\FilamentMenuBuilder\Models\Menu;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('filament-menu-builder.tables.menus'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create(config('filament-menu-builder.tables.menu_items'), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Menu::class)->constrained()->cascadeOnDelete();
            // $table->foreignIdFor(Menu::class, 'parent_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable();
            $table->nullableMorphs('linkable');
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('target', 10)->default(LinkTarget::Self);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('set null');
        });

        Schema::create(config('filament-menu-builder.tables.menu_locations'), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Menu::class)->constrained()->cascadeOnDelete();
            $table->string('location')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('filament-menu-builder.tables.menu_locations'));
        Schema::dropIfExists(config('filament-menu-builder.tables.menu_items'));
        Schema::dropIfExists(config('filament-menu-builder.tables.menus'));
    }
};
