# Development Guide

## Local Development Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/riodwanto/superduper-filament-starter-kit.git
   cd superduper-filament-starter-kit
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install NPM dependencies:
   ```bash
   npm install
   ```

4. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database in `.env`

6. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   php artisan shield:generate --all
   php artisan db:seed --class=PermissionsSeeder
   ```

7. Build assets:
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

## Code Style

This project follows PSR-12 coding standards. Run the following commands to check and fix code style:

```bash
composer check-style
composer fix-style
```

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=TestCaseName

# Run with coverage (requires XDebug)
XDEBUG_MODE=coverage php artisan test --coverage-html=coverage
```

### Browser Testing

This project uses Laravel Dusk for browser testing:

```bash
# Install Dusk
composer require --dev laravel/dusk
php artisan dusk:install

# Run Dusk tests
php artisan dusk
```

## Creating a New Module

1. Generate a new Filament resource:
   ```bash
   php artisan make:filament-resource ResourceName
   ```

2. Register the resource in `App\Providers\Filament\AdminPanelProvider.php`

3. Create migrations, models, and policies as needed

## Customizing the Theme

Edit `resources/css/filament.css` for custom styles:

```css
@config "../../tailwind.config.js";
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom styles */
.fi-sidebar {
    @apply bg-primary-600 text-white;
}
```

## API Development

### Generating API Documentation

```bash
# Install API documentation generator
composer require --dev mpociot/laravel-apidoc-generator

# Generate documentation
php artisan apidoc:generate
```

### API Authentication

This project uses Laravel Sanctum for API authentication:

1. Get a token:
   ```http
   POST /api/auth/login
   Content-Type: application/json

   {
       "email": "user@example.com",
       "password": "password"
   }
   ```

2. Use the token in subsequent requests:
   ```http
   GET /api/user
   Authorization: Bearer your-token-here
   ```

## Deployment

### Production Setup

1. Set up your production environment variables
2. Install dependencies with `--no-dev`:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
3. Optimize the framework:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. Build production assets:
   ```bash
   npm run build
   ```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request
