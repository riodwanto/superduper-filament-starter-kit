<?php

namespace App\Console\Commands\Superduper;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'superduper:install';

    protected $description = 'Quick installation with default values';

    public function handle()
    {
        $this->info('🚀 Quick Installing SuperDuper...');
        $this->newLine();

        // Run setup with default flag
        $exitCode = $this->call('superduper:setup', [
            '--default' => true,
            '--env-backup' => true,
        ]);

        return $exitCode;
    }
}
