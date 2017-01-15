<?php

namespace KilroyWeb\Permissions;

abstract class BasePermission{

    protected $id;
    protected $label;

    public function getLabel(){
        if($this->label){
            return $this->label;
        }
        return $this->labelFromClassName();
    }

    public static function id(){
        $instance = new static;
        return $instance->getId();
    }

    public function getId(){
        if($this->id){
            return $this->id;
        }
        return $this->idFromClassName();
    }

    private function idFromClassName(){
        $reflect = new \ReflectionClass(get_class($this));
        $name = $reflect->getShortName();
        return $name;
    }

    private function labelFromClassName(){
        $reflect = new \ReflectionClass(get_class($this));
        $name = $reflect->getShortName();
        $name = $this->formatClassNameToLabel($name);
        return $name;
    }

    private function formatClassNameToLabel($className){
        $label = $className;
        $label = $this->formatUppercaseToSpaceUppercase($label);
        $label = ucwords($label);
        return $label;
    }

    private function formatUppercaseToSpaceUppercase($string){
        $pieces = preg_split('/(?=[A-Z])/',$string);
        return implode(' ',$pieces);
    }

}