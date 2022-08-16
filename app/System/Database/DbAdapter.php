<?php
namespace App\System\Database;

use App\System\Registry;

class DbAdapter
{
    private $defaultConnection;
    public $config;


    public function __construct()
    {
        $this->config = Registry::get('config');
        $connectionType = empty($this->config['db']['dbAdapter']) ? 'PDO' : $this->config['db']['dbAdapter'];
        $this->setDefaultConnection($connectionType);
    }

    /**
     * @param $connectionType
     *
     * @return \App\System\Database\Adapters\MYSQLI|\App\System\Database\Adapters\PDO|\App\System\PDO
     */
    private function connectionFactory($connectionType)
    {
        switch ($connectionType) {
            case 'PDO':
                $instance = \App\System\Database\Adapters\PDO::getInstance();
                break;
            case 'MYSQLI':
                $instance = \App\System\Database\Adapters\MYSQLI::getInstance();
                break;
            default:
                $instance = \App\System\Database\Adapters\PDO::getInstance();
        }

        return $instance;
    }

    /**
     * @param $connectionType
     *
     * @return void
     */
    private function setDefaultConnection($connectionType)
    {
        $this->defaultConnection = $this->connectionFactory($connectionType);
    }

    public function getDefaultConnection()
    {
        return $this->defaultConnection;
    }

    public function __destruct()
    {
        $this->defaultConnection::closeConnection();
    }
}