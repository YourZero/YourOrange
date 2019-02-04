<?php

namespace YourOrange;

use Dotenv\Dotenv;

class DB
{
    /**
     * @var \PDO
     */
    protected $pdo;

    protected $cachedQuery;

    /**
     * DB constructor.
     * @param $host
     * @param $database
     * @param $user
     * @param $password
     * @param $driver
     */
    public function __construct($host, $database, $user, $password, $driver)
    {
        $dotenv = new Dotenv('../');
        $dotenv->load();


        $this->pdo = new \PDO("{$driver}:dbname={$database};host={$host}", $user, $password);
    }

    /**
     * @return DB
     */
    public static function getInstance()
    {
        static $instance = null;
        if ($instance instanceof self) {
            return $instance;
        }

        return $instance = new self('mysql', 'your_orange', 'your_orange', 'Archer!01', 'mysql');
    }

    /**
     * @param $stmt
     * @param $params
     * @return bool|\PDOStatement
     */
    protected function prepared($stmt, $params)
    {
        $stmtH = $this->pdo->prepare($stmt);
        $stmtH->execute($params);
        return $stmtH;
    }

    /**
     * @param $stmt
     * @param $params
     * @return array
     */
    protected function assoc($stmt, $params)
    {
        return $this->prepared($stmt, $params)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $stmt
     * @param $params
     * @return array
     */
    public function row($stmt, $params)
    {
        $result = $this->assoc($stmt, $params);
        return reset($result);
    }

    /**
     * @param $stmt
     * @return bool
     */
    public function query($stmt)
    {
        return $this->pdo->prepare($stmt)->execute();
    }

    /**
     * @param $stmt
     * @param $params
     * @return array
     */
    public function all($stmt, $params)
    {
        return $this->assoc($stmt, $params);
    }

    /**
     * @param $stmt
     * @param $params
     * @return array
     */
    public function column($stmt, $params)
    {
        $data = [];
        foreach ($this->all($stmt, $params) as $v) {
            $data[] = reset($v);
        }
        return $data;
    }

    /**
     * @param $table
     * @param $params
     * @return bool|int
     */
    public function insert($table, $params)
    {
        $keys = array_keys($params);
        $escapedKeys = $keys;
        foreach($escapedKeys as &$v) {
            $v = "`{$v}`";
        }
        $keyString = implode(',', $escapedKeys);
        $values = [];
        foreach ($keys as $value) {
            $values[] = ":{$value}";
        }

        $valueString = implode(',', $values);
        $stmt = "insert into {$table} ({$keyString}) values ({$valueString});";
        $stmtH = $this->pdo->prepare($stmt);
        var_dump($stmt, $params);
        if(!$stmtH->execute($params)){
            var_dump($stmtH->errorInfo());exit;
        }
    }

    /**
     * @param $table
     * @param $params
     * @param $where
     * @param $whereParams
     * @return bool|int
        */
        public function update($table, $params, $where, $whereParams)
    {
        $values = [];
        foreach (array_keys($params) as $value) {
            $values[] = "`{$value}`=:{$value}";
        }

        $keyValueString = implode(',', $values);
        $stmt = "update {$table} set {$keyValueString} where {$where};";
        $stmtH = $this->pdo->prepare($stmt);
        if (!$stmtH->execute(array_merge($params, $whereParams))) {
            return false;
        }

        return $stmtH->rowCount();
    }


}