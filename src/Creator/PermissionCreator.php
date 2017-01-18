<?php

namespace KilroyWeb\Permissions\Creator;

class PermissionCreator{

    protected $interface;
    protected $className;
    protected $config;

    public function __construct(){
        $this->config = new \KilroyWeb\Permissions\Configuration\PermissionConfig();
    }

    public function setInterface($interface){
        $this->interface = $interface;
    }

    public function setClassName($className){
        $this->className = $className;
    }

    public function createPermissionsFile(){
        $this->interface->info('Creating Permissions File');
        $putFilePath = app_path().$this->config->getPermissionAppDirectory().'/'.$this->className.'.php';
        if(file_exists($putFilePath)){
            $this->interface->error('File Already Exists: '.$putFilePath);
        }else{
            $templateFilePath = __DIR__.'/Templates/PermissionClass.php.txt';
            $templateFile = file_get_contents($templateFilePath);
            $fileContents = str_replace('[CLASSNAME]',$this->className,$templateFile);
            $this->preparePermissionDirectory();
            \File::put($putFilePath,$fileContents);
        }
    }

    public function preparePermissionDirectory(){
        $directory = $this->config->getPermissionAppDirectory();
        if (!is_dir(app_path().$directory)) {
            mkdir(app_path().$directory,0777, true);
        }
    }

    public function addPermissionToPermissionsConfig(){
        $this->interface->info('Adding Permission To Permissions Config');
        $permissionsFilePath = config_path().'/permissions.php';
        $permissionsContents = file_get_contents($permissionsFilePath);
        $lineSearch = "'available' => [";
        $newLine = PHP_EOL.'        App\Permissions\\'.$this->className.'::class,';
        if(strstr($permissionsContents,$newLine)){
            $this->interface->error('Permission already in Config');
        }else{
            $lineReplace = $lineSearch.$newLine;
            $permissionsContents = str_replace($lineSearch,$lineReplace,$permissionsContents);
            \File::put($permissionsFilePath,$permissionsContents);
        }
    }

    public function create(){
        $this->createPermissionsFile();
        $this->addPermissionToPermissionsConfig();
        $this->interface->info('Done!');
    }

}