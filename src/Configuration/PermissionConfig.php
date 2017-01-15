<?php

namespace KilroyWeb\Permissions\Configuration;

class PermissionConfig{

    private $permissionAppDirectory = '/Permissions';

    public function getPermissionAppDirectory(){
        return $this->permissionAppDirectory;
    }

}