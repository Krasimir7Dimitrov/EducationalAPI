<?php
namespace App\System\Database\Interfaces;


interface DbAdapterInterface
{

    public function fetchOne($sql, $data);

    public function fetchAll($sql, $data);

    public function insert($table, $data);

    public function update($table, $where, $data);

    public function delete($table, $where);

    public static function closeConnection();

}