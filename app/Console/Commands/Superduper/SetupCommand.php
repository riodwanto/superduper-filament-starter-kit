<?php

namespace App\Console\Commands\Superduper;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class SetupCommand extends Command
{
    protected $signature = 'superduper:setup
                            {--default : Use default values without prompting}
                            {--fresh : Run fresh migrations}
                            {--env-backup : Always backup .env file}
                            {--skip-npm : Skip npm installation}
                            {--skip-npm-build : Skip npm build}
                            {--skip-migrations : Skip database migrations}
                            {--skip-seed : Skip database seeding}';

    protected $description = 'Setup SuperDuper Filament 3 Starter Kit';

    public function handle()
    {
        $this->displayBanner();

        // Production check
        if ($this->isProduction()) {
            $this->error('🚨 This setup command should not be run on production environments!');
            $this->error('Running this command on production could cause data loss.');
            return self::FAILURE;
        }

        // Skip warning in default mode
        if (!$this->option('default')) {
            $this->info('⚠️  WARNING: This command should NOT be run on production servers.');
            if (!$this->confirm('Are you sure want countinue?', false)) {
                $this->error('Setup aborted.');
                return self::FAILURE;
            }
        }

        // Run setup tasks
        $this->info('🚀 Starting Setup Process');
        $this->newLine();

        $tasks = [
            'Setting up environment file' => fn() => $this->setupEnvFile(),
            'Database Configuration' => fn() => $this->configureDatabaseSettings(),
            'Generating application key' => fn() => $this->generateAppKey(),
            'Linking storage' => fn() => $this->linkStorage(),
            'Running migrations' => fn() => $this->runMigrations(),
            'Seeding database' => fn() => $this->seedDatabase(),
            'Clearing cache' => fn() => $this->clearCache(),
            'Generating Shield components' => fn() => $this->generateShieldComponents(),
            'Seeding permissions to roles' => fn() => $this->seedPermissions(),
        ];

        $results = [];

        foreach ($tasks as $name => $task) {
            $this->info("📌 {$name}...");

            try {
                $result = $task();
                $results[$name] = $result;

                if ($result) {
                    $this->info("   ✅ Success");
                } else {
                    $this->error("   ❌ Failed");

                    if (!$this->option('default') && !$this->confirm('Task failed. Do you want to continue?', true)) {
                        $this->error('Setup aborted.');
                        return self::FAILURE;
                    }
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Error: " . $e->getMessage());
                $results[$name] = false;

                if (!$this->option('default') && !$this->confirm('Task failed. Do you want to continue?', true)) {
                    return self::FAILURE;
                }
            }

            $this->newLine();
        }

        // Summary
        $this->displaySummary($results);

        return self::SUCCESS;
    }

    protected function displayBanner()
    {
        $composerJson = json_decode(file_get_contents(base_path('composer.json')), true);
        $version = $composerJson['version'] ?? '1.0.0';

        $this->line('
<fg=cyan;options=bold>
░█▀▀░█░█░█▀█░█▀▀░█▀▄░█▀▄░█░█░█▀█░█▀▀░█▀▄
░▀▀█░█░█░█▀▀░█▀▀░█▀▄░█░█░█░█░█▀▀░█▀▀░█▀▄
░▀▀▀░▀▀▀░▀░░░▀▀▀░▀░▀░▀▀░░▀▀▀░▀░░░▀▀▀░▀░▀
    Filament 3 Starter Kit v' . $version . '
</>
        ');
    }

    protected function isProduction()
    {
        return app()->environment('production');
    }

    protected function installComposerPackages()
    {
        if ($this->option('skip-npm')) {
            $this->info('   Skipped (--skip-npm flag)');
            return true;
        }

        $result = Process::tty()->run('composer install');
        return $result->successful();
    }

    protected function installNpmPackages()
    {
        if ($this->option('skip-npm')) {
            $this->info('   Skipped (--skip-npm flag)');
            return true;
        }

        $result = Process::tty()->run('npm install');
        return $result->successful();
    }

    protected function setupEnvFile()
    {
        if (File::exists('.env')) {
            // Default mode OR --env-backup flag: Always backup
            if ($this->option('default') || $this->option('env-backup')) {
                $backupName = '.env.backup.' . date('YmdHis');
                File::copy('.env', $backupName);
                $this->info("   📁 Backed up to {$backupName}");
                File::copy('.env.example', '.env');
                return true;
            }

            // Interactive mode: Ask user
            if ($this->confirm('   .env file exists. Backup and overwrite?', true)) {
                $backupName = '.env.backup.' . date('YmdHis');
                File::copy('.env', $backupName);
                $this->info("   📁 Backed up to {$backupName}");
            } else {
                $this->info('   Using existing .env file');
                return true;
            }
        }

        File::copy('.env.example', '.env');
        return true;
    }

    protected function configureDatabaseSettings()
    {
        // Default mode: Use .env values, display as table
        if ($this->option('default')) {
            // Set default database name if not set
            $env = File::get('.env');
            if (!preg_match('/DB_DATABASE=(.+)/', $env) || preg_match('/DB_DATABASE=laravel/', $env)) {
                $env = preg_replace(
                    '/DB_DATABASE=(.*)/',
                    'DB_DATABASE=superduper_filament_starter_kit',
                    $env
                );
                File::put('.env', $env);
            }

            $this->call('config:clear');

            // Force reload environment variables
            $this->reloadEnvConfig();

            $connection = config('database.default');
            $dbConfig = config('database.connections.' . $connection);

            // Get password from .env (show masked or empty)
            $envContent = File::get('.env');
            preg_match('/DB_PASSWORD=(.*)/', $envContent, $passwordMatch);
            $password = isset($passwordMatch[1]) && !empty($passwordMatch[1]) ? '••••••••' : '(empty)';

            $this->info('   Using database configuration:');
            $this->newLine();
            $this->table(
                ['Setting', 'Value'],
                [
                    ['Connection', $connection],
                    ['Host', $dbConfig['host'] ?? '127.0.0.1'],
                    ['Port', $dbConfig['port'] ?? '3306'],
                    ['Database', $dbConfig['database'] ?? 'superduper_filament_starter_kit'],
                    ['Username', $dbConfig['username'] ?? 'root'],
                    ['Password', $password],
                ]
            );

            return true;
        }

        // Interactive mode: Ask for configuration
        $this->newLine();
        $this->info('🛢️  Database Configuration');

        $connections = ['mysql', 'sqlite', 'pgsql', 'sqlsrv'];

        $connection = $this->choice(
            'Select database connection',
            $connections,
            0
        );

        $config = [
            'DB_CONNECTION' => $connection,
            'DB_HOST' => $this->ask('Database host', '127.0.0.1'),
            'DB_PORT' => $this->ask('Database port', $connection === 'mysql' ? '3306' : '5432'),
            'DB_DATABASE' => $this->ask('Database name', 'superduper_filament_starter_kit'),
            'DB_USERNAME' => $this->ask('Database username', 'root'),
            'DB_PASSWORD' => $this->secret('Database password'),
        ];

        // Update .env file
        $env = File::get('.env');

        foreach ($config as $key => $value) {
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $env
            );
        }

        File::put('.env', $env);
        $this->call('config:clear');

        // Force reload environment variables
        $this->reloadEnvConfig();

        // Display configuration as table
        $this->newLine();
        $this->info('   Database configured:');
        $this->newLine();
        $this->table(
            ['Setting', 'Value'],
            [
                ['Connection', $config['DB_CONNECTION']],
                ['Host', $config['DB_HOST']],
                ['Port', $config['DB_PORT']],
                ['Database', $config['DB_DATABASE']],
                ['Username', $config['DB_USERNAME']],
                ['Password', !empty($config['DB_PASSWORD']) ? '••••••••' : '(empty)'],
            ]
        );

        return true;
    }

    /**
     * Reload environment configuration
     */
    protected function reloadEnvConfig()
    {
        // Read updated .env
        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        // Parse and set environment variables
        foreach (explode("\n", $envContent) as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // Parse KEY=VALUE
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Update runtime environment
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;

                // Update config for database settings
                if (str_starts_with($key, 'DB_')) {
                    $configKey = strtolower(str_replace('DB_', '', $key));
                    config(["database.connections." . config('database.default') . ".{$configKey}" => $value]);
                }
            }
        }
    }

    protected function generateAppKey()
    {
        $envPath = base_path('.env');
        $env = File::get($envPath);

        // Ensure APP_KEY line exists
        if (!preg_match('/^APP_KEY=/m', $env)) {
            $this->info('   APP_KEY line missing, adding it...');

            // Add APP_KEY at the end
            $env = rtrim($env) . "\nAPP_KEY=\n";
            File::put($envPath, $env);

            // Clear caches and reload
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            // Reload environment
            $this->reloadEnvConfig();
        }

        // Generate key
        $exitCode = $this->call('key:generate');

        return $exitCode === 0;
    }

    protected function linkStorage()
    {
        if (File::exists(public_path('storage'))) {
            $this->info('   Storage link already exists');
            return true;
        }

        $this->call('storage:link');
        return true;
    }

    protected function runMigrations()
    {
        if ($this->option('skip-migrations')) {
            $this->info('   Skipped (--skip-migrations flag)');
            return true;
        }

        $this->call('config:clear');

        // Default mode: Always use standard migrate
        if ($this->option('default')) {
            $this->info('   Running standard migration...');
            return $this->call('migrate', ['--force' => true]) === 0;
        }

        // Interactive mode: Show options
        $this->newLine();
        $this->info('How would you like to run migrations?');
        $this->line('  <fg=cyan>[0]</> Standard migration <fg=gray>(php artisan migrate)</>');
        $this->line('  <fg=cyan>[1]</> Fresh - drops all tables <fg=gray>(php artisan migrate:fresh)</>');
        $this->line('  <fg=cyan>[2]</> Refresh - rollback & re-run <fg=gray>(php artisan migrate:refresh)</>');
        $this->line('  <fg=cyan>[3]</> Skip migrations');

        $choice = $this->ask('Enter your choice', $this->option('fresh') ? '1' : '0');

        return match($choice) {
            '0' => $this->call('migrate') === 0,
            '1' => $this->call('migrate:fresh') === 0,
            '2' => $this->call('migrate:refresh') === 0,
            '3' => true,
            default => $this->call('migrate') === 0,
        };
    }

    protected function seedDatabase()
    {
        if ($this->option('skip-seed')) {
            $this->info('   Skipped (--skip-seed flag)');
            return true;
        }

        // Default mode: Always seed
        if ($this->option('default')) {
            $this->info('   Seeding database...');
            return $this->call('db:seed', ['--force' => true]) === 0;
        }

        // Interactive: Ask user
        if (!$this->confirm('   Would you like to seed the database?', true)) {
            return true;
        }

        return $this->call('db:seed') === 0;
    }

    protected function clearCache()
    {
        $this->call('optimize:clear');
        return true;
    }

    protected function buildFrontend()
    {
        if ($this->option('skip-npm') || $this->option('skip-npm-build')) {
            $this->info('   Skipped');
            $this->info('   You can run "npm run dev" or "npm run build" manually');
            return true;
        }

        // Default mode: Always skip build
        if ($this->option('default')) {
            $this->info('   Skipped (default mode)');
            $this->info('   You can run "npm run dev" or "npm run build" manually');
            return true;
        }

        // Interactive: Ask user
        if (!$this->confirm('   Build frontend assets?', false)) {
            $this->info('   You can run "npm run dev" or "npm run build" manually');
            $this->newLine(2);
            return true;
        }

        $result = Process::tty()->run('npm run build');
        return $result->successful();
    }

    protected function generateShieldComponents()
    {
        return $this->call('shield:generate', ['--all' => true]) === 0;
    }

    protected function seedPermissions()
    {
        return $this->call('db:seed', ['--class' => 'PermissionsSeeder']) === 0;
    }

    protected function displaySummary(array $results)
    {
        $this->newLine(2);
        $this->info('📋 Setup Summary');
        $this->newLine();

        $allSuccess = true;

        foreach ($results as $task => $success) {
            $status = $success
                ? '<fg=green>Success</>'
                : '<fg=red>Failed</>';

            $this->line("   " . str_pad($task, 35) . " : {$status}");

            if (!$success) {
                $allSuccess = false;
            }
        }

        $this->newLine();

        if ($allSuccess) {
            $this->info('🥳 All tasks completed successfully!');
            $this->newLine();
            $this->info('To run the Laravel development server:');
            $this->newLine();
            $this->line('  <fg=green>php artisan serve</>');
            $this->newLine();
        } else {
            $this->error('⚠️  Some tasks failed. Please check the logs for details.');
        }
    }
}
