# Configuration

## Environment Variables

### Application

```env
APP_NAME="SuperDuper Filament Starter Kit"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### Database

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Mail

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Cache

```env
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Panel Configuration

### Admin Panel

Customize the admin panel in `config/filament.php`:

```php
return [
    'path' => 'admin',
    'auth' => [
        'guard' => 'web',
        'pages' => [
            'login' => \App\Filament\Pages\Auth\Login::class,
        ],
    ],
];
```

### Theme Customization

Edit `tailwind.config.js` to customize the theme:

```javascript
import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f0f9ff',
                    // ... other colors
                    900: '#0c4a6e',
                },
            },
        },
    },
}
```

## File Storage

Configure file storage in `config/filesystems.php`:

```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
    // ... other disks
],
```

## Caching

For production, configure a proper cache driver in `.env`:

```env
CACHE_DRIVER=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## Localization

Supported locales are configured in `config/app.php`:

```php
'locale' => 'en',
'fallback_locale' => 'en',
'available_locales' => [
    'en' => 'English',
    'es' => 'Spanish',
    'fr' => 'French',
],
```

