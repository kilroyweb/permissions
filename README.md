# Permissions

Class-based authorization for Laravel

## Installation

Add to providers:

KilroyWeb\Permissions\Providers\PermissionServiceProvider::class,

publish config:

php artisan vendor:publish --tag=config

## Generate Permissions

php artisan make:permission CLASSNAME

ie:

php artisan make:permission ManageUsers