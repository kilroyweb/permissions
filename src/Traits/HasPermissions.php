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

    public function addPermission($permissionInstance){
        if(!$this->hasPermission($permissionInstance)){
            $permissionClassName = $this->permissionClassNameByInstance($permissionInstance);
            \App\UserPermission::create([
                'user_id' => $this->id,
                'permission' => $permissionClassName,
            ]);
        }
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

    public function hasPermission($permissionInstance){
        $permissionClassName = $this->permissionClassNameByInstance($permissionInstance);
        $existingPermission = $this->permissionClassNames()->where('permission',$permissionClassName)->first();
        if($existingPermission){
            return true;
        }
        return false;
    }

}