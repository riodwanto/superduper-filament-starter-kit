<?php

namespace App\Console\Commands\Superduper;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PermissionsCommand extends Command
{
    protected $signature = 'superduper:permissions
                            {--fresh : Regenerate all permissions from scratch}';

    protected $description = 'Generate and seed permissions for Shield';

    public function handle()
    {
        $this->info('🛡️ Managing Shield Permissions...');
        $this->newLine();

        // If fresh flag, regenerate all permissions
        if ($this->option('fresh')) {
            $this->info('📝 Regenerating Shield permissions...');

            $exitCode = $this->call('shield:generate', ['--all' => true]);

            if ($exitCode === 0) {
                $this->info('   ✅ Shield permissions generated successfully');
            } else {
                $this->error('   ❌ Failed to generate Shield permissions');
                return self::FAILURE;
            }

            $this->newLine();
        }

        // Seed permissions to roles
        $this->info('🌱 Seeding permissions to roles...');

        $exitCode = $this->call('db:seed', ['--class' => 'PermissionsSeeder']);

        if ($exitCode === 0) {
            $this->info('   ✅ Permissions seeded successfully');
            $this->newLine();
            $this->info('🎉 All permissions have been configured!');
        } else {
            $this->error('   ❌ Failed to seed permissions');
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
