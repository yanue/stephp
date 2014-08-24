<?php
namespace Library\Fluent;

class FluentStructure
{

    private $primaryKey, $foreignKey;

    private $attributes = array();

    function __construct($primaryKey = 'id', $foreignKey = '%s_id')
    {
        if ($foreignKey === null) {
            $foreignKey = $primaryKey;
        }
        $this->primaryKey = $primaryKey;
        $this->foreignKey = $foreignKey;
    }

    public function getPrimaryKey($table)
    {
        return $this->key($this->primaryKey, $table);
    }

    public function getForeignKey($table)
    {
        return $this->key($this->foreignKey, $table);
    }

    private function key($key, $table)
    {
        if (is_callable($key)) {
            return $key($table);
        }
        return sprintf($key, $table);
    }

    /**
     * set a attribute
     * @param string $key
     * @param mixed $val
     * @return boolean
     */
    public function setAttribute($key, $val)
    {
        $this->attributes[$key] = $val;
        return true;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
        return null;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
