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

## Adding Permissions To A Role

Create a new migration:

```
Schema::create('role_permissions', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->string('permission');
    $table->timestamps();
});
```

## Adding Permissions To A User

Create a new migration: 

```
Schema::create('user_permissions', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id');
    $table->string('permission');
    $table->timestamps();
});
```