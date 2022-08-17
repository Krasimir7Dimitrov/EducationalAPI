<?php

namespace App\System\Database\Adapters;

use App\System\Database\Interfaces\DbAdapterInterface;

class PDO implements DbAdapterInterface
{

    private static $instance;

    public $connection;

    const HOST = "db";
    const PORT = "3306";
    const DATABASE = "db";
    const USER = "user";
    const PASSWORD = "password";

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
            $connection = new \PDO('mysql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DATABASE, self::USER, self::PASSWORD);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Throwable $e) {
            throw new \Exception('Can\'t establish mysql connection');
        }

        return $connection;
    }


    public function insert($table, array $data): int
    {
        if (empty($data)) {
            false;
        }

        $singleRecord = false;
        foreach ($data as $array) {
            if (is_array($array)) {
                $keys = array_keys($array);
                break;
            } else {
                $keys = array_keys($data);
                $singleRecord = true;
            }
        }

        if (empty($keys)) {
            throw new \Exception('There is not any values');
        }

        $insertQuery = "INSERT INTO $table (" . implode(', ', $keys) . ") VALUES";

        $allKeys = [];
        foreach ($data as $array) {
            if (is_array($array)) {
                $keys = array_keys($array);
                $allKeys[] = $keys;
            } else {
                $keys = array_keys($data);
                $allKeys[] = $keys;
                break;
            }
        }

        $allValues = [];
        $count = 0;
        foreach ($allKeys as $value) {
            $vals = array_values($value);
            if ($count === 0) {
                $allValues[] = "(:" . $count . implode(', :' . $count, $vals) . ")";
            } else {
                $allValues[] = ", (:" . $count . implode(', :' . $count, $vals) . ")";
            }
            $count++;
        }
        $valuesQuery = implode($allValues);

        $sth = $this->connection->prepare(
            $insertQuery . $valuesQuery
        );

        if ($singleRecord === true) {
            $key = array_keys($data);

            for ($x = 0; $x < count($data); $x++) {
                $sth->bindParam(':0' . $key[$x], $data[$key[$x]]);
            }
        } else {
            $count = 0;
            foreach ($data as $array) {
                $key = array_keys($array);

                for ($x = 0; $x < count($array); $x++) {
                    $sth->bindParam(':' . $count . $key[$x], $array[$key[$x]]);
                }
                $count++;
            }
        }
        $result = $sth->execute();

        if ($result) {
            $rowCount = $sth->rowCount();
            if ($rowCount) return (int)$this->connection->lastInsertId();
        }

        return false;
    }

    /**
     * @param $table
     * @param $where
     * @param $data
     * @return false|int
     */

    public function update($table, $where, $data): int
    {
        $query = "";
        if (!empty($where) and is_array($where)) {
            $whereVals = [];
            foreach ($where as $key => $val) {
                $whereVals[] = "$key = :w$key";
                $query = implode(" AND ", $whereVals);
            }
        } elseif (!empty($data) and is_integer($where)) {
            $query = "id = :id";
        } else {
            throw new \Exception('There is empty value or too many values');
        }

        $vals = [];
        foreach ($data as $key => $val) {
            $vals[] = "$key = :$key";
        }

        $sql = "UPDATE " . $table . " c SET " . implode(", ", $vals) . " WHERE 1 AND " . $query;
        $sth = $this->connection->prepare($sql);

        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $sth->bindParam(':w' . $key, $where[$key]);
            }
        } else {
            $sth->bindParam(':id', $where);
        }
        foreach ($data as $key => $value) {
            $sth->bindParam(':' . $key, $data[$key]);
        }

        $sth->execute();

        return (int)$sth->rowCount();
    }


    /**
     * @param $table
     * @param $where
     * @return false
     */
    public function delete($table, $where): bool
    {
        $addAnd = '';
        if (empty($where)) {
            return false;
        } else {
            $addAnd = ' AND ';
        }

        $whereCondition = $this->makeCondition($where);

        $statement = 'DELETE FROM ' . $table . ' WHERE 1'. $addAnd . implode(' AND ', $whereCondition);
        $query = $this->db->prepare($statement);

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
            $query->bindParam(':' . $key, $value, $paramTypeForBinding);
        }
    }
}