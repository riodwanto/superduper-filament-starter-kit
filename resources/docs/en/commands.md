# Commands

This document covers all available SuperDuper commands for managing your Filament application.

---

## Overview

SuperDuper provides a suite of Artisan commands to streamline the setup and management of your Filament application. All commands are namespaced under `superduper:` for easy discovery.

To see all available commands:

```bash
php artisan list superduper
```

---

## Available Commands

### superduper:setup

Complete interactive setup for the SuperDuper Filament Starter Kit.

**Prerequisites:**

- Composer dependencies must be installed (`composer install`)
- Node dependencies must be installed (`npm install`)

**Usage:**

```bash
php artisan superduper:setup
```

**Options:**

- `--default` - Use default values for quick install
- `--fresh` - Run fresh migrations (drops all tables)
- `--env-backup` - Always backup .env file
- `--skip-migrations` - Skip database migrations
- `--skip-seed` - Skip database seeding

**What it does:**

1. Checks if environment is production (aborts if true)
2. Sets up `.env` file (with backup option)
3. Configures database settings (interactive or uses .env defaults)
4. Generates application key
5. Creates storage symbolic link
6. Runs database migrations (with options)
7. Seeds database (optional)
8. Clears application cache
9. Generates Shield permissions and policies
10. Seeds permissions to roles

**Examples:**

```bash
# Interactive setup
php artisan superduper:setup

# Non-interactive with defaults
php artisan superduper:setup --default

# Always backup .env before overwriting
php artisan superduper:setup --env-backup

# Setup with fresh migrations
php artisan superduper:setup --fresh

# Skip specific steps
php artisan superduper:setup --skip-migrations --skip-seed
```

**Migration Options (Interactive Mode):**

- `[0]` Standard migration (php artisan migrate)
- `[1]` Fresh - drops all tables (php artisan migrate:fresh)
- `[2]` Refresh - rollback & re-run (php artisan migrate:refresh)
- `[3]` Skip migrations

**Default Mode Behavior:**

When using `--default` flag:

- Uses existing .env configuration
- Sets database name to `superduper_filament_starter_kit` if not configured
- Runs standard migrations automatically
- Seeds database automatically
- No prompts or user interaction required

---

### superduper:permissions

Generate and seed permissions for Shield.

**Usage:**

```bash
php artisan superduper:permissions [options]
```

**Options:**

- `--fresh` - Regenerate all permissions from scratch

**What it does:**

1. Generates Shield permissions for all resources, pages, and widgets
2. Creates custom permissions from config
3. Seeds permissions to roles

**Examples:**

```bash
# Regenerate and seed permissions
php artisan superduper:permissions --fresh

# Just seed permissions (no regeneration)
php artisan superduper:permissions
```

---

### superduper:lang-translate

Translate language files from one language to another using Google Translate.

**Usage:**

```bash
php artisan superduper:lang-translate {from} {to*} [options]
```

**Arguments:**

- `from` - Source language code (e.g., `en`)
- `to` - Target language code(s) - can specify multiple (e.g., `id ja ko`)

**Options:**

- `--file=` - Translate specific file only
- `--json` - Translate JSON language files only

**Examples:**

```bash
# Translate all PHP files from English to Indonesian and Japanese
php artisan superduper:lang-translate en id ja

# Translate only JSON files
php artisan superduper:lang-translate en id --json

# Translate specific file
php artisan superduper:lang-translate en id --file=validation.php

# Translate JSON to multiple languages
php artisan superduper:lang-translate en id ja ko zh --json
```

**File Structure:**

```bash
lang/
├── en/
│   ├── validation.php
│   └── auth.php
├── en.json
├── id/          # Created by translation
│   ├── validation.php
│   └── auth.php
└── id.json      # Created by translation
```

---

## Configuration

### Custom Permissions

Define custom permissions in `config/filament-shield.php`:

```php
'custom_permissions' => [
    'view_logs' => 'View Logs',
    'export_reports' => 'Export Reports',
    'manage_settings' => 'Manage Settings',
],
```

Then run:

```bash
php artisan superduper:permissions --fresh
```

### Database Configuration

The `superduper:setup` command supports:

- MySQL
- SQLite
- PostgreSQL
- SQL Server

**Interactive Mode:** Prompts for all database settings

**Default Mode:** Uses existing .env configuration or sets:

- Database name: `superduper_filament_starter_kit`
- Other settings from .env.example defaults

---

## Troubleshooting

### Command Not Found

```bash
# Clear cache and reload
php artisan config:clear
php artisan cache:clear

# List all commands
php artisan list superduper
```

### Database Connection Issues

```bash
# After changing .env, clear config
php artisan config:clear

# Then re-run migrations
php artisan migrate
```

### Permission Issues

```bash
# Regenerate all permissions
php artisan superduper:permissions --fresh

# Check existing permissions
php artisan permission:show
```

### APP_KEY Missing Error

If you get "Unable to set application key" error:

```bash
# Ensure .env.example has APP_KEY= line
# Then re-run setup
php artisan superduper:setup
```

---

## Notes

- **Prerequisites**: Always run `composer install` and `npm install` BEFORE using setup commands
- **Production Safety**: `superduper:setup` and `superduper:install` check for production environment and abort if detected
- **Backup**: Setup command offers to backup existing `.env` file before overwriting (or use `--env-backup` flag)
- **Interactive Prompts**: Commands preserve Laravel's interactive prompts (e.g., "Create database?")
- **Config Cache**: Commands automatically clear config cache when needed
- **Frontend Build**: Run `npm run dev` or `npm run build` separately - not included in setup

---

## Contributing

To add new SuperDuper commands:

1. Create command in `app/Console/Commands/Superduper/`
2. Use namespace: `App\Console\Commands\Superduper`
3. Prefix signature with `superduper:`
4. Laravel will auto-discover the command

Example:

```php
<?php

namespace App\Console\Commands\Superduper;

use Illuminate\Console\Command;

class YourCommand extends Command
{
    protected $signature = 'superduper:your-command';
    protected $description = 'Your command description';

    public function handle()
    {
        // Your logic here
    }
}
```
