<?php

namespace App\System;

use App\System\Database\DbAdapter;
use App\System\Database\Interfaces\DbAdapterInterface;

class BaseCollection
{
    /** @var $db DbAdapterInterface */
    protected $db;
    protected $table = 'none';

    public function __construct()
    {
        /** @var  $dbAdapter DbAdapter */
        $dbAdapter = Registry::get('dbAdapter');
        $this->db = $dbAdapter->getDefaultConnection();
    }

    public function update($where, $data)
    {
        return $this->db->update($this->table, $where, $data);
    }
}