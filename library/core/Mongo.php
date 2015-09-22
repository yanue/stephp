<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 9/8/15
 * Time: 11:15
 */

namespace Library\Core;


if (!defined('LIB_PATH')) exit('No direct script access allowed');

use Closure;
use Library\Db\Odm\Monga;
use Library\Db\Odm\Monga\Collection;

abstract class Mongo
{
    private static $db = [];
    /**
     * @var \Library\Db\Odm\Monga\Collection
     */
    private static $collection = null;

    /**
     * 初始化
     */
    final private function __construct()
    {
        self::collection();
    }

    /**
     * @param null $database
     * @param $collection
     * @return Collection
     */
    final public static function connect($database = null, $collection)
    {
        if (empty($database)) {
            $database = Config::getSite('mongo', 'db.defaultDb');
            $database = $database ? $database : "default";
        }

        if (empty(self::$db)) {
            $config = Config::getSite('mongo', $database);
            if (!is_array($config)) {
                die("数据库配置错误");
            }
            $db_server = $config['db_server'];
            $db_option = $config['db_options'];
            $db_name = $config['db_name'];
            self::$db = Monga::connection($db_server, $db_option);
            $db = self::$db->database($db_name);
            self::$collection = $db->collection($collection);
        }

        return self::$collection;
    }

    /**
     * 获取集合
     * -- 根据表切换数据库
     *
     * @return Collection
     */
    final protected static function collection()
    {
        $reflection = new \ReflectionClass(get_called_class());
        $defaultProp = $reflection->getDefaultProperties();
        $table = str_replace('Mongo', '', $reflection->getShortName());
        if (!isset($defaultProp['collectionName'])) {
            preg_match_all('/((?:^|[A-Z])[a-z]+)/', $table, $matches);
            $table = strtolower(implode('_', $matches[0]));
        } else {
            $table = $defaultProp['collectionName'];
        }
        $db = empty($defaultProp['database']) ? '' : $defaultProp['database'];
        $a = self::connect($db, $table);

        unset($modelName);
        unset($reflection);
        unset($matches);
        return $a;
    }

    /**
     * @param array $query
     * @param array $fields
     * @return array|null
     */
    public static function findFirst($query = [], $fields = [])
    {
        return self::collection()->findOne($query, $fields);
    }

    /**
     * @param array $query
     * @param array $fields
     * @return mixed
     */
    public static function find(array $query = array(), array $fields = array())
    {
        return self::collection()->find($query)->fields($fields);
    }

    /**
     * @param array $query
     * @param array $fields
     * @return mixed
     */
    public static function all(array $query = array(), array $fields = array())
    {
        return self::collection()->find($query)->fields($fields);
    }

    /**
     * @param $key
     * @param array $query
     * @return array
     */
    public static function distinct($key, $query = [])
    {
        return self::collection()->distinct($key, $query);
    }

    /**
     * @param array $aggregation
     * @return array
     */
    public static function aggregate($aggregation = [])
    {
        return self::collection()->aggregate($aggregation);
    }

    /**
     * @return bool
     */
    public static function truncate()
    {
        return self::collection()->truncate();
    }

    /**
     * @param Closure $callback
     * @return object
     */
    public static function indexes(Closure $callback)
    {
        return self::collection()->indexes($callback);
    }

    /**
     * @return array
     */
    public static function listIndexes()
    {
        return self::collection()->listIndexes();
    }

    /**
     * @param array $query
     * @return int
     */
    public static function count($query = array())
    {
        return self::collection()->count($query);
    }

    /**
     * @param array $criteria
     * @param array $options
     * @return mixed
     */
    public static function del(array $criteria = array(), array $options = array('w' => true, 'multiple' => TRUE))
    {
        return self::collection()->remove($criteria, $options);
    }

    /**
     * @param array $values
     * @param null $query
     * @param array $options
     * @return bool
     */
    public static function update($values = [], $query = null, $options = [])
    {
        return self::collection()->update($values, $query, $options);
    }

    /**
     * @param $data
     * @param array $options
     * @return bool
     */
    public static function create($data, $options = array('w' => true))
    {
        return self::collection()->insert($data, $options);
    }

    /**
     * @param $document
     * @param array $options
     * @return bool
     */
    public static function save(&$document, $options = [])
    {
        return self::collection()->save($document, $options);
    }
}