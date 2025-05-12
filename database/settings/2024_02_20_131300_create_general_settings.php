<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.brand_name', 'SuperDuper Starter Kit');
        $this->migrator->add('general.brand_logo', 'sites/logo.png');
        $this->migrator->add('general.brand_logoHeight', '100');
        $this->migrator->add('general.site_favicon', 'sites/logo.ico');
        $this->migrator->add('general.search_engine_indexing', false);
        $this->migrator->add('general.site_theme', [
            "primary" => "#2D2B8D",
            "secondary" => "#FFC903",
            "gray" => "#0a0700",
            "success" => "#10B981",
            "danger" => "#EF4444",
            "info" => "#3B82F6",
            "warning" => "#F59E0B",
        ]);
    }
};
