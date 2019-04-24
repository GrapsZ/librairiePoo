<?php

class Core {

    /**
     * Init Main website's services
     */
    public function initCore() {

        $kernel = Kernel::getInstance();

        $kernel->generateSettings();
        $kernel->initConnection();
        $kernel->initLibrary();
    }
}