# Installation

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Database (MySQL/PostgreSQL/SQLite)
- Web Server (Apache/Nginx) or PHP's built-in server

## Quick Start

Create a new project using Composer:

```bash
composer create-project riodwanto/superduper-filament-starter-kit
```

## Setup Wizard

Run the interactive setup script:

```bash
php bin/setup.php
```

## Manual Setup

1. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

2. Generate application key:
   ```bash
   php artisan key:generate
   ```

3. Configure your database in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```

5. Generate Shield permissions:
   ```bash
   php artisan shield:generate --all
   php artisan db:seed --class=PermissionsSeeder
   ```

6. Create storage link:
   ```bash
   php artisan storage:link
   ```

7. Install frontend dependencies:
   ```bash
   npm install
   ```

8. Build assets:
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

## Default Login Credentials

After installation, you can log in with:

- **Email:** superadmin@starter-kit.com
- **Password:** superadmin

Access the admin panel at: `http://localhost:8000/admin`
