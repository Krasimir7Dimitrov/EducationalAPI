<?php

namespace App\System;

use App\Library\Debugbar\DebugData;
use App\Library\Debugbar\Interfaces\DebugDataInterface;
use App\System\Database\DbAdapter;

class Application
{
    private static $instance;
    private FrontController $frontController;

    /**
     * @throws \Exception
     */
    private function __construct()
    {
        // here we will initialize our Registry
        try {
            $config = require __DIR__.'/../config/config.php';
            Registry::set('config', $config);

            $dbAdapter = new DbAdapter();
            Registry::set('dbAdapter', $dbAdapter);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->frontController = new FrontController();
    }


    public static function getInstance()
    {
        if (is_null(self::$instance)) {
           self::$instance = new Application();
        }

        return self::$instance;
    }

    public function run()
    {
        return $this->frontController->run();
    }
}