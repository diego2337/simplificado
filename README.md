<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to run

1. Install both [PHP8](https://www.php.net/releases/8.0/en.php) and [Composer 2](https://getcomposer.org/download/).
2. Run `composer install`.
3. Run `sail up --build -d` and you're all set.

## Transaction
This is a simple Laravel application which uses [Nwidart Modules](https://nwidart.com/laravel-modules/v6/introduction) to decouple the application's main feature. Within the `Transaction` module lies all business logic to validate and transfer a given amount between users.

## Insomnia collection
You can use the [Insomnia Collection](https://github.com/diego2337/simplificado/blob/develop/simplificado.json) to test the request.
## Pending

1. [Logging](https://laravel.com/docs/8.x/logging).
2. Unit tests for `User` and `Role` and layers (`Service`, `Repository`).
3. Feature and unit tests for `Transaction` module.
