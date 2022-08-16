<?php

namespace App\System;

use App\Library\Debugbar\DebugData;
use App\Library\Debugbar\Interfaces\DebugDataInterface;
use App\System\Database\DbAdapter;
use Pecee\SimpleRouter\SimpleRouter;

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
        /* Load external routes file */
        require_once __DIR__.'/../config/routes.php';

        // Start the routing
        try {
            SimpleRouter::start();
        } catch (\Throwable $exception)  {
            $message = $exception->getMessage();
            $code    = $exception->getCode();

            header("HTTP/1.1 ".$code." ".$message , );
        }
    }
}