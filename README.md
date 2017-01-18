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

and model:

```
php artisan make:model RolePermission
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

and model:

```
php artisan make:model UserPermission
```

## Add HasPermissions Trait

Add the HasPermissions trait to your User/Role model:

```
use \KilroyWeb\Permissions\Traits\HasPermissions;
```

Along with a pointer to the permission model:

```
protected $permissionsClass = \App\UserPermission::class;
```

The trait provides a few useful properties + methods:

$user->permissionClassNames

uses Laravel's hasmany to return the direct linked permission classes

$user->permissions

Returns a collection of permission class instances

Methods for adding/deleting permissions:

$user->addPermission($permissionInstance);
$user->deletePermission($permissionInstance);
$user->syncPermissions($permissionInstances);

