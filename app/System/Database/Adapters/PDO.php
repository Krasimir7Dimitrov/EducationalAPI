<?php

namespace App\System\Database\Adapters;

use App\System\Database\Interfaces\DbAdapterInterface;

class PDO implements DbAdapterInterface
{

    private static $instance;

    public $connection;

    const HOST          = "db";
    const PORT          = "3306";
    const DATABASE      = "db";
    const USER          = "user";
    const PASSWORD      = "password";

    /**
     * @throws \Exception
     */
    private function __construct()
    {
        $this->connection = $this->getConnection();
    }


    public function fetchOne($sql, $data = [])
    {
        $stm = $this->connection->prepare($sql);
        $this->paramsBindingHelper($data, $stm);
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_ASSOC);
    }


    public function fetchAll($sql, $data = [])
    {
        $stm = $this->connection->prepare($sql);
        $this->paramsBindingHelper($data, $stm);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return \App\System\Database\Adapters\PDO
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDO();
        }

        return self::$instance;
    }

    /**
     * @return \PDO
     * @throws \Exception
     */
    private function getConnection()
    {
        try {
            $connection = new \PDO('mysql:host='. self::HOST . ';port=' .self::PORT. ';dbname='.self::DATABASE, self::USER, self::PASSWORD);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Throwable $e) {
            throw new \Exception('Can\'t establish mysql connection');
        }

        return $connection;
    }

    /**
     * @param $data
     * @return false|int
     */
    public function insert($table, $data)
    {
        if (empty($data)) {
            return false;
        }

        $arrayKeys = array_keys($data);
        $statement = 'INSERT INTO ' . $table . '(' . implode(", ", $arrayKeys) . ') VALUES (:'. implode(", :", $arrayKeys) .')';
        $query = $this->connection->prepare($statement);

        $this->paramsBindingHelper($data, $query);
        $result = $query->execute();

        if ($result) {
            $rowCount = $query->rowCount();
            if ($rowCount) return (int) $this->connection->lastInsertId();
        }

        return false;
    }

    /**
     * @param $table
     * @param $where
     * @param $data
     * @return false|int
     */
    public function update($table, $where, $data)
    {
        if (empty($data)) {
            return false;
        }

        $set = $this->makeCondition($data);

        $whereArray = [];
        foreach ($where as $key => $value) {
            $whereArray[] = "$key = :w$key";
        }

        $addAnd = '';
        if (!empty($where)) {
            $addAnd = ' AND ';
        }

        $statement = 'UPDATE ' . $table . ' SET '. implode(', ', $set) .' WHERE 1 '. $addAnd .implode(' AND ', $whereArray);
        $query = $this->connection->prepare($statement);

        $this->paramsBindingHelper($data, $query);

        foreach ($where as $key => $value) {
            $paramTypeForBinding = self::getParamTypeForBinding($value);
            $query->bindParam(':w'. $key, $value, $paramTypeForBinding);
        }

        $query->execute();

        return $query->rowCount();
    }

    /**
     * @param $table
     * @param $where
     * @return false|int
     */
    public function delete($table, $where)
    {
        $addAnd = '';
        if (empty($where)) {
            return false;
        } else {
            $addAnd = ' AND ';
        }

        $whereCondition = $this->makeCondition($where);

        $statement = 'DELETE FROM ' . $table . ' WHERE 1'. $addAnd . implode(' AND ', $whereCondition);
        $query = $this->connection->prepare($statement);

        foreach ($where as $key => $value) {
            $query->bindParam(':'. $key, $value);
        }

        $query->execute();

        return $query->rowCount();
    }

    /**
     * @param array $condition
     * @return array
     */
    public function makeCondition(array $condition): array
    {
        $conditionArray = [];
        foreach ($condition as $key => $value) {
            $conditionArray[] = "$key = :$key";
        }
        return $conditionArray;
    }

    public static function closeConnection()
    {
        self::$instance = null;
    }

    /**
     * @param $value
     * @return int|null
     * @author Hristo Stoyanov <hstoyanov@advisebrokers.com>
     */
    private static function getParamTypeForBinding($value)
    : ?int
    {
        switch (gettype($value)) {
            case 'integer':
                return \PDO::PARAM_INT;
            case 'string':
                return \PDO::PARAM_STR;
            default:
                return null;
        }
    }

    /**
     * @param $data
     * @param $query
     * @author Hristo Stoyanov <hstoyanov@advisebrokers.com>
     */
    private function paramsBindingHelper($data, $query)
    : void
    {
        foreach ($data as $key => $value) {
            $paramTypeForBinding = self::getParamTypeForBinding($value);
            $query->bindValue(':' . $key, $value, $paramTypeForBinding);
        }
    }
}