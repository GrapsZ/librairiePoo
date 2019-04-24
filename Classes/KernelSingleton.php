<?php

class KernelSingleton {

    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * @return KernelSingleton|null
     */
    public static function getInstance() {
        if (is_null(KernelSingleton::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}