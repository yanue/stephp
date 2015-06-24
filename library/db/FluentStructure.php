<?php
namespace Library\Db;

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

    public function setPrimaryKey($key)
    {
        $this->primaryKey = $key;
    }

    public function key($key, $table)
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
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * batch setting the attributes
     * @param array $data
     */
    public function setAttributes(array $data)
    {
        $this->attributes = array_merge($this->attributes, $data);
    }

    public function clearAttributes()
    {
        unset($this->attributes);
        $this->attributes = array();
        return true;
    }

    public function removeAttribute($key)
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
        }
        return true;
    }
}
