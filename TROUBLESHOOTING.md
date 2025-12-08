# 🔧 AgriConnect Troubleshooting Guide

## Common Issues and Solutions

### 🗄️ Database Issues

#### Issue: SQLSTATE[HY000] [1045] Access denied for user
**Solution:**
1. Check your `.env` file for correct database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agriconnect_db
DB_USERNAME=root
DB_PASSWORD=
```

2. Ensure MySQL is running:
```bash
# Windows (XAMPP)
Start XAMPP Control Panel > Start MySQL

# Linux/Mac
sudo service mysql start
```

3. Create the database if it doesn't exist:
```sql
CREATE DATABASE agriconnect_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

#### Issue: SQLSTATE[42S02]: Base table or view not found
**Solution:**
Run migrations to create all tables:
```bash
php artisan migrate:fresh
php artisan db:seed
```

---

#### Issue: Foreign key constraint fails
**Solution:**
1. Clear all tables and re-run migrations:
```bash
php artisan migrate:fresh --seed
```

2. If persists, check migration order in `database/migrations/` folder

---

### 🖼️ Storage & File Upload Issues

#### Issue: Images not displaying / 404 error for uploaded images
**Solution:**
1. Create storage symlink:
```bash
php artisan storage:link
```

2. Verify link exists:
```bash
# Windows
dir public\storage

# Linux/Mac
ls -la public/storage
```

3. Check storage permissions:
```bash
# Linux/Mac only
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

#### Issue: "The stream or file could not be opened" error
**Solution:**
1. Set correct permissions:
```bash
# Windows (run as administrator)
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T

# Linux/Mac
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
```

2. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

### 🔐 Authentication Issues

#### Issue: 419 Page Expired / CSRF token mismatch
**Solution:**
1. Clear all caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

2. Regenerate key:
```bash
php artisan key:generate
```

3. Check session configuration in `.env`:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

4. Clear browser cookies and cache

---

#### Issue: User redirected to wrong dashboard after login
**Solution:**
1. Check `app/Http/Controllers/Auth/LoginController.php`:
```php
protected function authenticated(Request $request, $user)
{
    if ($user->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role == 'farmer') {
        return redirect()->route('farmer.dashboard');
    } elseif ($user->role == 'buyer') {
        return redirect()->route('buyer.dashboard');
    }
}
```

2. Verify routes exist in `routes/web.php`

---

### 📦 Package & Dependency Issues

#### Issue: Class not found errors
**Solution:**
1. Regenerate autoload files:
```bash
composer dump-autoload
```

2. Clear compiled classes:
```bash
php artisan clear-compiled
php artisan optimize:clear
```

3. Reinstall dependencies:
```bash
rm -rf vendor
composer install
```

---

#### Issue: npm run dev/build fails
**Solution:**
1. Delete node_modules and reinstall:
```bash
# Windows
rmdir /s node_modules
del package-lock.json
npm install

# Linux/Mac
rm -rf node_modules package-lock.json
npm install
```

2. Clear npm cache:
```bash
npm cache clean --force
```

3. Check Node.js version:
```bash
node --version  # Should be 16.x or higher
```

---

### 🎨 Frontend Issues

#### Issue: Tailwind CSS styles not applying
**Solution:**
1. Rebuild assets:
```bash
npm run build
```

2. Check `vite.config.js` includes all template paths:
```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

3. Ensure Tailwind config includes all paths:
```javascript
// tailwind.config.js
content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
],
```

---

#### Issue: JavaScript not working / Console errors
**Solution:**
1. Check browser console for specific errors
2. Ensure Alpine.js is loaded in `resources/js/app.js`
3. Rebuild assets:
```bash
npm run dev
```

---

### 🔄 Performance Issues

#### Issue: Application running slowly
**Solution:**
1. Enable caching:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. Optimize autoloader:
```bash
composer install --optimize-autoloader --no-dev
```

3. Check database queries:
```php
// Add to AppServiceProvider boot() method for debugging
\DB::listen(function ($query) {
    \Log::info($query->sql, $query->bindings, $query->time);
});
```

4. Use eager loading to prevent N+1 queries:
```php
// Bad
$farmers = Farmer::all();
foreach ($farmers as $farmer) {
    echo $farmer->user->name; // N+1 problem
}

// Good
$farmers = Farmer::with('user')->get();
```

---

### 🚀 Deployment Issues

#### Issue: 500 Internal Server Error in production
**Solution:**
1. Check Laravel log:
```bash
tail -f storage/logs/laravel.log
```

2. Enable debug mode temporarily:
```env
APP_DEBUG=true  # Set back to false after debugging
```

3. Check file permissions:
```bash
# Ensure web server can write to these directories
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

4. Regenerate cache:
```bash
php artisan optimize:clear
php artisan optimize
```

---

#### Issue: Routes not working (404 errors)
**Solution:**
1. Check `.htaccess` file exists in `public/` folder
2. Enable Apache mod_rewrite:
```bash
# Linux/Mac
sudo a2enmod rewrite
sudo service apache2 restart
```

3. For Nginx, ensure proper configuration:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

---

### 📧 Email Issues

#### Issue: Emails not sending
**Solution:**
1. Check mail configuration in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@agriconnect.com
MAIL_FROM_NAME="${APP_NAME}"
```

2. For Gmail, use App Password instead of regular password
3. Test mail configuration:
```bash
php artisan tinker
>>> Mail::raw('Test email', function ($message) {
>>>     $message->to('test@example.com')->subject('Test');
>>> });
```

---

### 🔍 Search & Filter Issues

#### Issue: Search not returning results
**Solution:**
1. Check database has data:
```sql
SELECT COUNT(*) FROM products WHERE status = 'Available';
```

2. Verify search query in controller:
```php
// app/Http/Controllers/BuyerController.php
if ($request->search) {
    $query->where('product_name', 'LIKE', "%{$request->search}%")
          ->orWhere('egg_type', 'LIKE', "%{$request->search}%");
}
```

3. Check form method and route:
```html
<form method="GET" action="{{ route('buyer.dashboard') }}">
```

---

### 🔄 Matching System Issues

#### Issue: Products not matching with demands
**Solution:**
1. Verify matching service is registered:
```php
// app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->singleton(MatchingService::class, function ($app) {
        return new MatchingService();
    });
}
```

2. Check matching triggers in Product model:
```php
// app/Models/Product.php
protected static function boot()
{
    parent::boot();
    
    static::created(function ($product) {
        if ($product->status === 'Available') {
            app()->make(MatchingService::class)->matchNewProductWithDemands($product);
        }
    });
}
```

3. Test matching manually:
```bash
php artisan tinker
>>> $product = Product::find(1);
>>> app(MatchingService::class)->matchNewProductWithDemands($product);
```

---

## 🛠️ General Debugging Tips

### 1. Enable Debug Mode
In `.env` file:
```env
APP_DEBUG=true
APP_ENV=local
```

### 2. Check Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Apache logs (XAMPP)
tail -f /xampp/apache/logs/error_log

# MySQL logs
tail -f /xampp/mysql/data/mysql_error.log
```

### 3. Use Laravel Debugbar
Install for better debugging:
```bash
composer require barryvdh/laravel-debugbar --dev
```

### 4. Test in Tinker
```bash
php artisan tinker
>>> User::count()
>>> Product::with('farmer')->first()
>>> DB::table('products')->get()
```

### 5. Clear Everything
```bash
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

---

## 📞 Getting Help

If issues persist:

1. **Check Laravel Documentation**: https://laravel.com/docs
2. **Search Error Message**: Copy exact error and search online
3. **Laravel Forums**: https://laracasts.com/discuss
4. **Stack Overflow**: Tag with `laravel` and `php`
5. **GitHub Issues**: Report bugs with reproduction steps

### Information to Provide When Seeking Help:
- Laravel version: `php artisan --version`
- PHP version: `php -v`
- Error message (complete)
- Relevant code snippets
- What you've already tried
- `.env` configuration (remove sensitive data)
- Database structure
- Browser console errors (for frontend issues)

---

## 🔄 Reset Everything

If all else fails, complete reset:

```bash
# Backup important files first!

# 1. Clear all caches
php artisan optimize:clear

# 2. Remove vendor and node_modules
rm -rf vendor node_modules

# 3. Remove compiled files
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

# 4. Reinstall everything
composer install
npm install

# 5. Regenerate everything
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed
npm run build

# 6. Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

---

**Last Updated**: December 2024  
**Version**: 1.0.0
