
## Laravel 9.x
<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Dashboard 

Dashboard is a web application that allows you to manage your projects, tasks, and team members. It is built with Laravel and livewire full page Alpine.js and Tailwind CSS.
- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Admin and User different page](https://localhost/).
- [Docker-compose](https://docker.compose).
- [Mysql-server](https://localhost:8001/).
- [Laravel livewire](https://laravel-livewire.com/).
- [vite.js](https://vitejs.dev/).
- [jetstream](https://jetstream.laravel.com/1.x/introduction.html).

## Installation

```php
cd exen-app
 
./vendor/bin/sail up
```

```php
npm install && npm run dev
```

```php
http://localhost/ 
mysqli::localhost:8001
```

Different Page for Admin and User Router:

```php
User: http://localhost/user/dashboard

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:user'])->group(function () {
    Route::get('/user/dashboard',UserDashboardComponent::class)->name('user.dashboard');
});


Admin: http://localhost/admin

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'role:admin'])->group(function () {
    Route::get('/admin',AdminDashboardComponent::class)->name('user.admin');
});

```

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send me e-mail to  [burrakkozcan@proton.me](mailto:burrakkozcan@proton.me). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
