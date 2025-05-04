<?php

// ANSI color codes
define('COLOR_GREEN', "\033[32m");
define('COLOR_RED', "\033[31m");
define('COLOR_YELLOW', "\033[33m");
define('COLOR_BLUE', "\033[34m");
define('COLOR_CYAN', "\033[36m");
define('COLOR_RESET', "\033[0m");
define('COLOR_BOLD', "\033[1m");
define('COLOR_GRAY', "\033[38;5;245m");
define('COLOR_LIGHTER_GRAY', "\033[37m");
define('COLOR_LIGHTEST_GRAY', "\033[37;1m");
// Others
define('STDIN_PATH', "php://stdin");

if (isset($_SERVER['REQUEST_METHOD'])) {
    header('HTTP/1.0 403 Forbidden');
    echo "Access Forbidden";
    exit(1);
}

echo COLOR_CYAN . COLOR_BOLD . "
░█▀▀░█░█░█▀█░█▀▀░█▀▄░█▀▄░█░█░█▀█░█▀▀░█▀▄░░░█▀▀░█▀▀░▀█▀░█░█░█▀█
░▀▀█░█░█░█▀▀░█▀▀░█▀▄░█░█░█░█░█▀▀░█▀▀░█▀▄░░░▀▀█░█▀▀░░█░░█░█░█▀▀
░▀▀▀░▀▀▀░▀░░░▀▀▀░▀░▀░▀▀░░▀▀▀░▀░░░▀▀▀░▀░▀░░░▀▀▀░▀▀▀░░▀░░▀▀▀░▀░░
" . COLOR_RESET . "\n";

function isProduction()
{
    // # Check for environment variable
    if (getenv('APP_ENV') === 'production') {
        return true;
    }

    // # Check Laravel .env file if it exists
    if (file_exists('.env') && strpos(file_get_contents('.env'), 'APP_ENV=production') !== false) {
        return true;
    }

    return false;
}

if (isProduction()) {
    echo COLOR_RED . "🚨 This setup script should not be run on production environments!" . COLOR_RESET . "\n";
    echo COLOR_RED . "Running this script on production could cause data loss." . COLOR_RESET . "\n";
    exit(1);
}

if (!confirm("⚠️ WARNING: This script should NOT be run on production servers. Are you sure you're on a development or staging environment?")) {
    echo COLOR_RED . "Setup aborted." . COLOR_RESET . "\n";
    exit(1);
}

function executeCommand($command, string|null $msg = null, bool $silent = false, bool $interactive = false)
{
    if (!$silent) {
        echo COLOR_GREEN . ($msg ?? "Executing: $command") . COLOR_RESET . "\n";
    }

    $success = true;

    // For interactive commands, use passthru
    if ($interactive) {
        passthru($command, $returnStatus);
        $success = $returnStatus === 0;
    } else {
        // For non-interactive commands, use exec
        exec($command, $output, $returnStatus);
        $success = $returnStatus === 0;

        if ($success && !$silent && is_array($output)) {
            foreach ($output as $line) {
                echo "  " . $line . PHP_EOL;
            }
        }
    }

    if (!$success) {
        echo COLOR_RED . "🚨🚨🚨 Error occurred while executing: $command" . COLOR_RESET . "\n";
    }

    return $success;
}

function confirm($question)
{
    echo COLOR_YELLOW . "$question [Y/n]: " . COLOR_RESET;
    $handle = fopen(STDIN_PATH, "r");
    $line = trim(fgets($handle));
    fclose($handle);
    return strtolower($line) === 'y' || $line === '';
}

// Function to ask for input with default value
function askWithDefault($question, $default = '')
{
    $defaultDisplay = !empty($default) ? " [$default]" : '';
    echo COLOR_YELLOW . "$question$defaultDisplay: " . COLOR_RESET;
    $handle = fopen(STDIN_PATH, "r");
    $line = trim(fgets($handle));
    fclose($handle);
    return $line !== '' ? $line : $default;
}

function configureDatabaseSettings($envPath)
{
    echo COLOR_BOLD . "\n🛢️ Database Configuration\n" . COLOR_RESET;

    // Check if can read the env file
    if (!file_exists($envPath)) {
        echo COLOR_RED . "⚠️ .env file not found at $envPath. Creating a new one..." . COLOR_RESET . "\n";
        if (!copy('.env.example', $envPath)) {
            echo COLOR_RED . "🚨🚨🚨 Failed to create new .env file" . COLOR_RESET . "\n";
            return false;
        }
    }

    $env = file_get_contents($envPath);
    if ($env === false) {
        echo COLOR_RED . "🚨🚨🚨 Failed to read .env file" . COLOR_RESET . "\n";
        return false;
    }

    // Extract current password from .env if it exists
    $currentPassword = '';
    if (preg_match('/DB_PASSWORD=([^\n]*)/', $env, $matches)) {
        $currentPassword = $matches[1];
    }

    // Default credentials from .env.example
    $defaultDb = [
        'connection' => preg_match('/DB_CONNECTION=([^\n]+)/', $env, $matches) ? $matches[1] : 'mysql',
        'host' => preg_match('/DB_HOST=([^\n]+)/', $env, $matches) ? $matches[1] : '127.0.0.1',
        'port' => preg_match('/DB_PORT=([^\n]+)/', $env, $matches) ? $matches[1] : '3306',
        'database' => preg_match('/DB_DATABASE=([^\n]+)/', $env, $matches) ? $matches[1] : 'laravel',
        'username' => preg_match('/DB_USERNAME=([^\n]+)/', $env, $matches) ? $matches[1] : 'root',
        'password' => $currentPassword, // Use the current password from .env
    ];

    echo COLOR_BLUE . "Please enter your database configuration details:" . COLOR_RESET . "\n";

    // Available database connections
    $dbConnections = ['mysql', 'sqlite', 'pgsql', 'sqlsrv'];
    echo COLOR_BLUE . "Available database connections:" . COLOR_RESET . "\n";
    foreach ($dbConnections as $index => $connection) {
        echo "  " . COLOR_CYAN . "[" . ($index + 1) . "]" . COLOR_RESET . " $connection\n";
    }

    // Ask for connection type
    $connectionIndex = askWithDefault("Enter database connection number", "1");
    $connectionIndex = (int) $connectionIndex;
    if ($connectionIndex < 1 || $connectionIndex > count($dbConnections)) {
        $connectionIndex = 1; // Default to mysql
    }
    $connection = $dbConnections[$connectionIndex - 1];

    $host = askWithDefault("Enter database host", $defaultDb['host']);
    $port = askWithDefault("Enter database port", $defaultDb['port']);
    $database = askWithDefault("Enter database name", $defaultDb['database']);
    $username = askWithDefault("Enter database username", $defaultDb['username']);

    if (empty($currentPassword)) {
        echo COLOR_YELLOW . "Enter database password [current password is empty]: " . COLOR_RESET;
    } else {
        echo COLOR_YELLOW . "Enter database password [current password is set]: " . COLOR_RESET;
    }

    system('stty -echo');
    $handle = fopen(STDIN_PATH, "r");
    $password = rtrim(fgets($handle));
    system('stty echo');
    echo "\n";
    fclose($handle);

    // Update .env file
    $env = preg_replace('/DB_CONNECTION=([^\n]+)/', "DB_CONNECTION=$connection", $env);
    $env = preg_replace('/DB_HOST=([^\n]+)/', "DB_HOST=$host", $env);
    $env = preg_replace('/DB_PORT=([^\n]+)/', "DB_PORT=$port", $env);
    $env = preg_replace('/DB_DATABASE=([^\n]+)/', "DB_DATABASE=$database", $env);
    $env = preg_replace('/DB_USERNAME=([^\n]+)/', "DB_USERNAME=$username", $env);

    // Handle password - if empty, keep the default
    if ($password !== '') {
        $env = preg_replace('/DB_PASSWORD=([^\n]*)/', "DB_PASSWORD=$password", $env);
    }

    // Write back to .env file
    if (file_put_contents($envPath, $env) === false) {
        echo COLOR_RED . "🚨🚨🚨 Failed to write database configuration to .env file" . COLOR_RESET . "\n";
        return false;
    }

    return true;
}

// Check composer.json exists
if (!file_exists('composer.json')) {
    echo COLOR_RED . "🚨🚨🚨 Please make sure to run this script from the root directory of this repo." . COLOR_RESET . "\n";
    exit(1);
}

// Check for .env file
$envExists = file_exists('.env');
$envPath = '.env';

if ($envExists) {
    echo COLOR_BLUE . "ℹ️ An .env file already exists." . COLOR_RESET . "\n";
    if (confirm("Would you like to backup the existing .env file?")) {
        $backupName = '.env.backup.' . date('YmdHis');
        copy('.env', $backupName);
        echo COLOR_GREEN . "📁 Backed up .env to $backupName" . COLOR_RESET . "\n";
    }

    // Always overwrite, no need to ask
    $envExists = false;
    echo COLOR_BLUE . "Will overwrite existing .env file with new configuration." . COLOR_RESET . "\n";
}

// Define the tasks
$tasks = [
    ['command' => 'composer install', 'message' => '⚗️ Installing composer packages...'],
    ['command' => 'npm install', 'message' => '⚗️ Installing npm packages...'],
    [
        'command' => null,
        'message' => '📰 Copying .env.example to .env...',
        'function' => function () use ($envExists, $envPath) {
            if (!$envExists) {
                if (copy('.env.example', $envPath)) {
                    return true;
                } else {
                    echo COLOR_RED . "🚨🚨🚨 Failed to copy .env.example to .env" . COLOR_RESET . "\n";
                    return false;
                }
            }
            return true;
        }
    ],
    [
        'command' => null,
        'message' => '🛢️ Configuring database settings...',
        'function' => function () use ($envPath) {
            return configureDatabaseSettings($envPath);
        }
    ],
    ['command' => 'php artisan key:generate', 'message' => '🔑 Generating application key...'],
    [
        'command' => null,
        'message' => '🔗 Linking storage...',
        'function' => function () {
            $publicStoragePath = 'public/storage';

            if (file_exists($publicStoragePath) && is_link($publicStoragePath)) {
                echo COLOR_BLUE . "ℹ️  Storage link already exists. Skipping..." . COLOR_RESET . "\n";
                return true;
            } else {
                echo COLOR_GREEN . "🔗 Creating storage link..." . COLOR_RESET . "\n";
                return executeCommand('php artisan storage:link', "🔗 Creating symbolic link for storage folder...", false, false);
            }
        }
    ],
    [
        'command' => null,
        'message' => '🗄️  Running database migrations...',
        'function' => function () {
            // Migrations Options
            echo COLOR_YELLOW . "\nHow would you like to run migrations?" . COLOR_RESET . "\n";
            echo "  " . COLOR_CYAN . "[1]" . COLOR_RESET . " Fresh migration - drops all tables (php artisan migrate:fresh)\n";
            echo "  " . COLOR_CYAN . "[2]" . COLOR_RESET . " Standard migration (php artisan migrate)\n";
            echo "  " . COLOR_CYAN . "[3]" . COLOR_RESET . " Refresh migration - rollback and re-run (php artisan migrate:refresh)\n";
            echo "  " . COLOR_CYAN . "[0]" . COLOR_RESET . " Skip migrations\n";

            echo COLOR_YELLOW . "Enter your choice [0]: " . COLOR_RESET;
            $handle = fopen(STDIN_PATH, "r");
            $line = trim(fgets($handle));
            fclose($handle);

            switch ($line) {
                case '1':
                    $migrationCommand = 'php artisan migrate:fresh';
                    $migrationMessage = '🗄️ Running fresh migrations (dropping all tables)...';
                    return executeCommand($migrationCommand, $migrationMessage, false, true);
                case '2':
                    $migrationCommand = 'php artisan migrate';
                    $migrationMessage = '🗄️ Running standard migrations...';
                    return executeCommand($migrationCommand, $migrationMessage, false, true);
                case '3':
                    $migrationCommand = 'php artisan migrate:refresh';
                    $migrationMessage = '🗄️ Running migration refresh (rollback and re-run)...';
                    return executeCommand($migrationCommand, $migrationMessage, false, true);
                default:
                    echo COLOR_BLUE . "Migrations skipped. You can run migrations manually later with:" . COLOR_RESET . "\n";
                    echo COLOR_GREEN . "  php artisan migrate" . COLOR_RESET . "\n";
                    return true;
            }
        }
    ],
    [
        'command' => null,
        'message' => '🌱 Running database seeding...',
        'function' => function () {
            // Seeding Options
            echo COLOR_YELLOW . "\nWould you like to seed the database?" . COLOR_RESET . "\n";
            echo "  " . COLOR_CYAN . "[1]" . COLOR_RESET . " Run database seeder\n";
            echo "  " . COLOR_CYAN . "[0]" . COLOR_RESET . " Skip seeding\n";

            echo COLOR_YELLOW . "Enter your choice [0]: " . COLOR_RESET;
            $handle = fopen(STDIN_PATH, "r");
            $line = trim(fgets($handle));
            fclose($handle);

            if ($line === '1') {
                return executeCommand('php artisan db:seed', '🌱 Seeding database...', false, true);
            } else {
                echo COLOR_BLUE . "Database seeding skipped. You can seed manually later with:" . COLOR_RESET . "\n";
                echo COLOR_GREEN . "  php artisan db:seed" . COLOR_RESET . "\n";
                return true;
            }
        }
    ],
    ['command' => 'php artisan optimize:clear', 'message' => '🧹 Clearing cache...'],
    [
        'command' => null,
        'message' => '🏗️ Deciding on npm build...',
        'function' => function () {
            echo COLOR_YELLOW . "\nHow would you like to handle frontend building?" . COLOR_RESET . "\n";
            echo "  " . COLOR_CYAN . "[1]" . COLOR_RESET . " Run npm build (production build)\n";
            echo "  " . COLOR_CYAN . "[0]" . COLOR_RESET . " Skip\n";

            echo COLOR_YELLOW . "Enter your choice [0]: " . COLOR_RESET;
            $handle = fopen(STDIN_PATH, "r");
            $line = trim(fgets($handle));
            fclose($handle);

            if ($line === '' || $line === '0') {
                echo COLOR_BLUE . "You can run 'npm run dev' or 'npm run build' manually later." . COLOR_RESET . "\n";
                return true;
            } else {
                // Run npm build
                return executeCommand('npm run build', '🏗️ Running npm build (production build)...', false, true);
            }

        }
    ],
];

// Run all tasks
echo COLOR_BOLD . "\n🚀 Starting Setup Process\n" . COLOR_RESET;

$taskResults = [];
$taskNames = [
    'Install composer packages',
    'Install npm packages',
    'Copy .env.example to .env',
    'Configure database settings',
    'Generate application key',
    'Link storage',
    'Run database migrations',
    'Seed database',
    'Clear cache',
    'NPM'
];

foreach ($tasks as $index => $task) {
    $taskKey = $index + 1;

    echo "\n";

    if (isset($task['function'])) {
        echo COLOR_GREEN . $task['message'] . COLOR_RESET . "\n";
        $taskResults[$taskKey] = $task['function']();
    } else {
        $interactive = isset($task['interactive']) && $task['interactive'];
        $taskResults[$taskKey] = executeCommand($task['command'], $task['message'], false, $interactive);
    }

    // If a task fails, ask whether to continue
    if (!$taskResults[$taskKey] && !confirm("Task failed. Do you want to continue with the remaining tasks?")) {
        echo COLOR_RED . "❌ Setup aborted." . COLOR_RESET . "\n";
        break;
    }
}

// Summary
echo COLOR_BOLD . "\n📋 Setup Summary\n" . COLOR_RESET;
$allSuccess = true;

foreach ($taskResults as $taskKey => $result) {
    $status = $result
        ? COLOR_GREEN . "Success" . COLOR_RESET
        : COLOR_RED . " Failed" . COLOR_RESET;

    if (!$result) {
        $allSuccess = false;
    }

    echo "   " . str_pad($taskNames[$taskKey - 1], 30) . " : " . $status . "\n";
}

if ($allSuccess) {
    echo COLOR_GREEN . "\n🥳 All tasks completed successfully!" . COLOR_RESET . "\n";

    echo COLOR_GREEN . "\n🛡️ Generating Shield components..." . COLOR_RESET . "\n";
    executeCommand('php artisan shield:generate --all', "🛡️ Running Shield component generation...", false, true);

    if (confirm("\nWould you like to disable this setup script to prevent accidental execution in the future?")) {
        $disabledPath = $_SERVER['SCRIPT_FILENAME'] . '.disabled';
        if (rename($_SERVER['SCRIPT_FILENAME'], $disabledPath)) {
            echo COLOR_GREEN . "✅ Setup script has been disabled and renamed to: " . basename($disabledPath) . COLOR_RESET . "\n";
            echo COLOR_YELLOW . "To re-enable, rename it back to its original name." . COLOR_RESET . "\n";
        } else {
            echo COLOR_RED . "❌ Failed to disable the setup script. You may want to remove it manually." . COLOR_RESET . "\n";
        }
    }

    echo COLOR_CYAN . "\nThank you for using the SuperDuper Starter Kit!\n" . COLOR_RESET;
} else {
    echo COLOR_RED . "\n⚠️ Some tasks failed. Please check the logs for details." . COLOR_RESET . "\n";
}

function getRepoDetailsFromUrl($gitUrl)
{
    // Parse the GitHub URL to get owner and repo
    if (preg_match('/github\.com[:|\/]([^\/]+)\/([^\.]+)\.git/', $gitUrl, $matches)) {
        return ['owner' => $matches[1], 'name' => $matches[2]];
    }
    return ['owner' => 'riodwanto', 'name' => 'superduper-filament-starter-kit'];
}

// Display GitHub contributors
function fetchGitHubContributors($repoOwner, $repoName, $limit = 10)
{
    // Check if curl is available
    if (!function_exists('curl_init')) {
        return false;
    }

    $url = "https://api.github.com/repos/{$repoOwner}/{$repoName}/contributors?per_page={$limit}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'SuperDuper Setup Script');
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Set timeout to 5 seconds

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$response || $httpCode !== 200) {
        return false;
    }

    return json_decode($response, true);
}

// Get repo details
$gitRemoteUrl = 'git@github.com:riodwanto/superduper-filament-starter-kit.git';
$repoDetails = getRepoDetailsFromUrl($gitRemoteUrl);
$repoOwner = $repoDetails['owner'];
$repoName = $repoDetails['name'];

if ($allSuccess) {
    function getAppUrl()
    {
        $appUrl = '';

        if (file_exists('.env')) {
            $env = file_get_contents('.env');
            if (preg_match('/APP_URL=([^\n]+)/', $env, $matches)) {
                return trim($matches[1]);
            }
        }

        return $appUrl;
    }

    $appUrl = getAppUrl();

    echo COLOR_BLUE . COLOR_BOLD . "\nTo run the Laravel development server:" . COLOR_RESET . "\n";
    echo COLOR_GREEN . "  php artisan serve" . COLOR_RESET . "\n\n";
    echo COLOR_YELLOW . "This will start the development server at " . COLOR_CYAN . $appUrl . COLOR_RESET . "\n";
    echo COLOR_YELLOW . "Press Ctrl+C to stop the server when running." . COLOR_RESET . "\n\n";

    echo COLOR_BLUE . "For advanced options, you can run:" . COLOR_RESET . "\n";
    echo COLOR_GREEN . "  php artisan serve --help" . COLOR_RESET . "\n\n";

    echo COLOR_BLUE . "You can also run the following commands during development:" . COLOR_RESET . "\n";
    echo COLOR_GREEN . "  npm run dev     " . COLOR_RESET . " - Start Vite development server with hot reloading\n";
    echo COLOR_GREEN . "  npm run build   " . COLOR_RESET . " - Build assets for production\n\n";

    // Contributors
    echo COLOR_LIGHTER_GRAY . COLOR_BOLD . "\nThanks to All Contributors" . COLOR_RESET . "\n";
    $contributors = fetchGitHubContributors($repoOwner, $repoName);
    if ($contributors) {
        foreach ($contributors as $contributor) {
            $username = $contributor['login'] ?? 'Unknown';
            echo "  " . COLOR_LIGHTER_GRAY . $username . COLOR_RESET . "\n";
        }
    }
    echo "\n" . COLOR_LIGHTER_GRAY . "Want to contribute?" . "\n" . "Visit: " . COLOR_LIGHTEST_GRAY . "https://github.com/{$repoOwner}/{$repoName}" . COLOR_RESET . "\n\n";
}
