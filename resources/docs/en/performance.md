# Performance Optimization

## Caching

### Configuration Cache

Cache your configuration for faster performance:

```bash
php artisan config:cache
```

### Route Caching

Cache your routes for faster routing:

```bash
php artisan route:cache
```

### View Caching

Pre-compile all Blade views:

```bash
php artisan view:cache
```

### Clear All Caches

```bash
php artisan optimize:clear
```

## Database Optimization

### Indexing

Ensure proper indexing on frequently queried columns:

```php
// In your migration
public function up()
{
    Schema::table('posts', function (Blueprint $table) {
        $table->index('slug');
        $table->index('published_at');
    });
}
```

### Eager Loading

Prevent N+1 query problems:

```php
// Instead of
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name;
}

// Use eager loading
$posts = Post::with('author')->get();
```

### Query Optimization

Use the query builder efficiently:

```php
// Instead of
Post::all()->count();

// Use
Post::count();
```

## Frontend Performance

### Asset Optimization

1. Minify CSS and JavaScript:
   ```bash
   npm run production
   ```

2. Enable gzip compression in your web server

3. Use a CDN for assets

### Image Optimization

1. Use responsive images:
   ```html
   <img 
       srcset="image-320w.jpg 320w,
               image-480w.jpg 480w,
               image-800w.jpg 800w"
       sizes="(max-width: 320px) 280px,
              (max-width: 480px) 440px,
              800px"
       src="image-800w.jpg"
       alt="Description">
   ```

2. Use modern image formats like WebP

## Server Configuration

### PHP-FPM Optimization

Edit `/etc/php/8.2/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 25
pm.start_servers = 5
pm.min_spare_servers = 2
pm.max_spare_servers = 10
```

### Nginx Configuration

Add to your Nginx server block:

```nginx
# Enable gzip
gzip on;
gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

# Enable caching
location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2|woff|ttf|svg|eot)$ {
    expires 30d;
    add_header Cache-Control "public, no-transform";
}
```

## Monitoring

### Laravel Telescope

Monitor your application with Laravel Telescope:

1. Install Telescope:
   ```bash
   composer require laravel/telescope
   php artisan telescope:install
   php artisan migrate
   ```

2. Access at `/telescope`

### Queue Workers

For better performance, use queue workers for long-running tasks:

```bash
# Start queue worker
php artisan queue:work --tries=3
```

## Profiling

### Laravel Debugbar

Profile your application with Laravel Debugbar:

1. Install Debugbar:
   ```bash
   composer require barryvdh/laravel-debugbar --dev
   ```

2. Access debug information at the bottom of your pages
