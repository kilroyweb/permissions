<?php

namespace KilroyWeb\Permissions\Traits;

trait HasPermissions{

    public function permissionRecords(){
        $permissionForeignKey = null;
        if(isset($this->permissionForeignKey)){
            $permissionForeignKey = $this->permissionForeignKey;
        }
        $permissionLocalKey = null;
        if(isset($this->permissionLocalKey)){
            $permissionLocalKey = $this->permissionLocalKey;
        }
        return $this->hasMany($this->permissionClass,$permissionForeignKey,$permissionLocalKey);
    }

    public function getPermissionsAttribute(){
        $permissions = collect();
        foreach($this->permissionRecords as $permissionRecord){
            $permission = $this->permissionInstanceByClassName($permissionRecord->permission);
            if($permission){
                $permissions->push($permission);
            }
        }
        return $permissions;
    }

    private function permissionInstanceByClassName($className){
        if(class_exists($className)){
            return new $className;
        }
        return false;
    }

    private function permissionClassNameByInstance($instance){
        return get_class($instance);
    }

    public function addPermission($permission){
        $permissionInstance = $this->getInstanceFromUnknown($permission);
        if(!$this->hasPermission($permissionInstance)){
            $permissionClassName = $this->permissionClassNameByInstance($permissionInstance);
            $permissionJoinClass = $this->permissionClass;
            $permissionJoinClass::create([
                $this->getPermissionForeignKey() => $this->id,
                $this->getPermissionLocalKey() => $permissionClassName,
            ]);
        }
    }

    private function getPermissionForeignKey(){
        if(isset($this->permissionForeignKey)){
            return $this->permissionForeignKey;
        }
        return $this->getForeignKey();
    }

    private function getPermissionLocalKey(){
        if(isset($this->permissionLocalKey)){
            return $this->permissionLocalKey;
        }
        return 'permission';
    }

    public function deletePermission($permissionInstance){
        $permissionClassName = $this->permissionClassNameByInstance($permissionInstance);
        $permissionRecords = $this->permissionRecords()->where('permission',$permissionClassName)->get();
        foreach($permissionRecords as $permissionRecord){
            $permissionRecord->delete();
        }
    }

    public function purgePermissions(){
        $permissionRecords = $this->permissionRecords;
        foreach($permissionRecords as $permissionRecord){
            $permissionRecord->delete();
        }
    }

    public function syncPermissions($permissionInstances=[]){
        $this->purgePermissions();
        $this->addPermissions($permissionInstances);
    }

    public function addPermissions($permissionInstances){
        foreach($permissionInstances as $permissionInstance){
            $this->addPermission($permissionInstance);
        }
    }

    public function hasPermission($permission){
        $permissionInstance = $this->getInstanceFromUnknown($permission);
        if($permissionInstance){
            $permissionClassName = $this->permissionClassNameByInstance($permissionInstance);
            $existingPermission = $this->permissionRecords()->where('permission','=',$permissionClassName)->first();
            if($existingPermission){
                return true;
            }
        }
        return false;
    }

    private function getInstanceFromUnknown($attribute){
        if(is_object($attribute)){
            return $attribute;
        }else{
            if(class_exists($attribute)){
                return new $attribute;
            }
        }
        return null;
    }

}
