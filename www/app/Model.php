<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 28/12/2018
 * Time: 18:59
 */

namespace YourOrange;

abstract class Model
{
    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $key = 'id';
    /**
     * @var array
     */
    // TODO: Figure out a way of specifying multiple columns on the maps - has one, has many...
    protected $whereColumns = [];
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var DB
     */
    protected $db;
    /**
     * @var mixed
     */
    protected $oldKey;
    /**
     * @var bool
     */
    protected $timeColumns = true;
    /**
     * @var array
     */
    protected $jsonColumns = [];


    /**
     * Model constructor.
     */
    public function __construct()
    {

        $this->db = DB::getInstance();
    }

    /**
     * @param $data
     */
    public function fill($data)
    {
        foreach ($data as $k => $v) {
            $this->data[$k] = $v;
        }
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function __set($key, $value)
    {
        return $this->data[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return $this->$key();
    }

    /**
     * @param $key
     * @return Model
     */
    public static function load($key)
    {
        $class = new static;
        $stmt = "select * from {$class->table} where {$class->key} = :key";
        $data = $class->db->row($stmt, ['key' => $key]);
        if (!empty($data)) {
            foreach($data as $k => $v) {
                if(in_array($k, $class->jsonColumns)) {
                    $data[$k] = json_decode($v);
                }
            }
            $class->fill($data);
            $class->oldKey = $key;
        }
        return $class;
    }

    /**
     * @param DB $db
     * @param $params
     * @param array $limit
     * @return Collection
     */
    public static function where(DB $db, $params, $limit = [])
    {
        $class = new static;
        $collection = new Collection;
        $whereArr = [];
        foreach ($params as $k => $v) {
            $whereArr[] = "{$k} = :{$k}";
        }
        $where = implode(' and ', $whereArr);
        $limitStmt = !empty($limit) ? " limit {$limit[0]}, {$limit[1]}" : "";
        $stmt = "select {$class->key} from {$class->table} where {$where} {$limitStmt}";
            var_dump($stmt);
        foreach ($db->column($stmt, $params) as $key) {
            $collection->push($class::load($key));
        }
        return $collection;
    }

    /**
     * @param $className
     * @param $foreignKey
     * @param $internalKey
     * @return Model
     */
    protected function hasOne($className, $foreignKey, $internalKey)
    {
        /**
         * @var Model $className
         */
        $class = new $className;
        $stmt = "select {$class->key} from {$class->table} where {$foreignKey} = :key";
        return $className::load($this->db->value($stmt, ['key' => $this->$internalKey]));
    }

    /**
     * @param $className
     * @param $foreignKey
     * @param $internalKey
     * @return Collection
     */
    protected function hasMany($className, $foreignKey, $internalKey)
    {
        /**
         * @var Model $className
         */
        $collection = new Collection;
        $class = new $className;
        $stmt = "select {$class->key} from {$class->table} where {$foreignKey} = :key";
        foreach ($this->db->column($stmt, ['key' => $this->$internalKey]) as $key) {
            $collection->push($className::load($key));
        }

        return $collection;
    }

    /**
     * @return bool|int
     */
    public function save()
    {
        if ($this->timeColumns) {
            $this->updatedAt = time();
        }
        $this->_save();
        foreach($this->data as $k => $v) {
            if(in_array($k, $this->jsonColumns)) {
                $this->$k = json_encode($v);
            }
        }

        if (isset($this->oldKey)) {
            return $this->update();
        }
        return $this->insert();
    }

    protected function _save()
    {
    }

    /**
     * @return bool|int
     */
    protected function update()
    {
        return $this->db->update($this->table, $this->data, "{$this->key} = :primaryKey", ['primaryKey' => $this->oldKey]);
    }

    /**
     * @return bool|int
     */
    protected function insert()
    {
        return $this->db->insert($this->table, $this->data);
    }


    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }


}