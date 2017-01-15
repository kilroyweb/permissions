<?php

namespace KilroyWeb\Permissions;

class PermissionRepository{

    public static function all(){
        $permissions = collect();
        $permissionClasses = config('permissions.available');
        if(is_array($permissionClasses)){
            foreach($permissionClasses as $permissionClass){
                $object = new $permissionClass;
                $permissions->push($object);
            }
        }
        $permissions = $permissions->sortBy(function($object, $key){
            return $object->getId();
        });
        return $permissions;
    }

}