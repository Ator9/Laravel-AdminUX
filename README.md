# Install Laravel
New Project
```sh
composer create-project laravel/laravel .
composer create-project laravel/laravel foldername
```
Existing Project
- clone it from github
- install dependencies
- copy .env.example to .env
- create database
```sh
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
```

# Install AdminUX
```sh
git init && git remote add adminux https://github.com/Ator9/Laravel-AdminUX.git && git pull adminux master
```
Add to /app/Http/Kernel.php - $routeMiddleware:
```php
'adminux' => \App\Adminux\Authenticate::class,
```
Add to /config/auth.php:
```php
// guards:
'adminux' => [
    'driver' => 'session',
    'provider' => 'adminux',
],

// providers:
'adminux' => [
    'driver' => 'eloquent',
    'model' => App\Adminux\Admin\Models\Admin::class,
],
```
Add to /routes/web.php:
```php
Route::prefix('admin')->group(function($router) {
    require base_path('app/Adminux/routes.php');
});
```

# Install Laravel Datatables
```sh
composer require yajra/laravel-datatables-oracle
```

## Artisan Console
```sh
php artisan route:list

php artisan make:controller AdminController
php artisan make:controller Admin/AdminController --resource

php artisan make:migration create_xxx_table
php artisan make:migration add_column_to_xxx_table --table="xxx"
```

Database Migrations - <a href="http://laravel.com/docs/migrations">Documentation</a>
```sh
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh
```

## .git/info/exclude
```sh
*

!app/*
app/*
!app/Adminux
!app/Adminux/*

!database/*
database/*
!database/migrations
database/migrations/*
!database/migrations/2019_01_01_000000_create_adminux.php

!public/*
public/*
!public/adminux/*

!resources/*
resources/*
!resources/views/*
resources/views/*
!resources/views/adminux/*
```
