<?php

class Autoloader {

    static function register() {
        spl_autoload_register(array(__CLASS__,"startAutoloader"));
    }

    /**
     * @param $className
     */
    static function startAutoloader($className){
        $path = './Classes/';
        include $path.$className.".php";
    }
}