<?php

namespace Library\Core;

use Library\Db\FluentPDO;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 数据处理模型类
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Model extends FluentPDO
{
    /**
     * @var FluentPDO
     */
    private static $db = null;

    /**
     * @var \PDO
     */
    private static $pdo = null;

    /**
     * 初始化
     */
    final public function __construct()
    {
        $this->connect();
        $this->setPdo(self::$pdo);
    }

    /**
     * 连接数据库
     *
     * @param null $dbType
     * @return FluentPDO
     */
    final public static function connect($dbType = null)
    {
        if (!self::$pdo) {
            if (empty($dbType)) {
                $dbType = Config::getSite('database', 'db.defaultSqlDriver');
                $dbType = $dbType ? $dbType : "mysql";
            }

            $config = Config::getSite('database', 'db.drivers.' . $dbType);
            if (!is_array($config)) {
                die("数据库配置错误");
            }

            $db_port = isset($config['port']) ? $config['port'] : '3306';
            $db_host = $config['host'];
            $db_name = $config['name'];
            $db_user = $config['user'];
            $db_pass = $config['pass'];

            $options = array(
                \PDO::ATTR_PERSISTENT => false, # ?
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, #err model, ERRMODE_SILENT 0,ERRMODE_EXCEPTION 2
            );

            $dsn = $dbType . ':dbname=' . $db_name . ';host=' . $db_host . ';port=' . $db_port;
            $pdo = new \PDO($dsn, $db_user, $db_pass, $options);
            $pdo->exec('set names \'utf8\'');

            $fpdo = new FluentPDO($pdo);

            self::$db = $fpdo;
            self::$pdo = $pdo;
        }

        return self::$db;
    }

    /**
     * 获取表名
     *
     * @return string
     */
    final  protected static function table()
    {
        $reflection = new \ReflectionClass(get_called_class());
        $modelName = $reflection->getShortName();
        $table = substr($modelName, 0, -5);
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $table, $matches);
        $table = strtolower(implode('_', $matches[0]));
        unset($modelName);
        unset($reflection);
        unset($matches);
        return $table;
    }


    /**
     * 获取一条数据
     *
     * @param null $where
     * @param null $columns
     * @param null $sort
     * @return mixed
     */
    final public static function findFirst($where = null, $columns = null, $sort = null)
    {
        $query = self::$db->from(self::table());
        if ($where) {
            $query = $query->where($where);
        }
        if ($columns) {
            $query->select(null)->select($columns);
        }
        if ($sort) {
            $query = $query->orderBy($sort);
        }

        return $query->fetch();
    }

    /**
     * 获取所有数据
     *
     * @param null $where
     * @param null $columns
     * @param null $sort
     * @return array
     */
    final public static function all($where = null, $columns = null, $sort = null)
    {
        $query = self::$db->from(self::table());
        if ($where) {
            $query = $query->where($where);
        }
        if ($columns) {
            $query->select(null)->select($columns);
        }
        if ($sort) {
            $query = $query->orderBy($sort);
        }

        return $query->fetchAll();
    }

    /**
     * 获取所有数据
     *
     * @param null $where
     * @param null $sort
     * @param int $page
     * @param int $limit
     * @return array
     */
    final public static function find($where = null, $sort = null, $page = 0, $limit = 10)
    {
        $query = self::$db->from(self::table());
        if ($where) {
            $query = $query->where($where);
        }
        if ($sort) {
            $query = $query->orderBy($sort);
        }

        if (is_numeric($page) && is_numeric($limit)) {
            $_where_page = $page <= 0 ? 0 : $page;
            $where_limit = sprintf("%s, %s", $_where_page * $limit, $limit);
            $query = $query->limit($where_limit);
        }

        return $query->fetchAll();
    }

    /**
     * sql查询
     *
     * @param $sql
     * @return mixed
     */
    final public static function fetchOne($sql)
    {
        return self::$db->getPdo()->query($sql)->fetch();
    }

    /**
     * sql批量查询
     *
     * @param $sql
     * @return array
     */
    final public static function fetchAll($sql)
    {
        return self::$db->getPdo()->query($sql)->fetchAll();
    }

    /**
     * 统计数量
     *
     * @param $where
     * @return int
     */
    final public static function count($where)
    {
        return self::$db->from(self::table())->where($where)->count();
    }

    public static function maximum($parameters = null)
    {
    }

    public static function minimum($parameters = null)
    {
    }

    public static function average($parameters = null)
    {
    }

    final public function save($data = null, $whiteList = null)
    {
    }

    /**
     * 删除数据
     *
     * @param $where
     * @return bool|\PDOStatement
     */
    final public static function del($where)
    {
        if ($where) {
            return self::$db->deleteFrom(self::table())->where($where)->execute();
        }
        return false;
    }

    /**
     * 更新数据
     *
     * @param $data
     * @param $where
     * @return bool|\PDOStatement
     * @throws Exception
     */
    final public static function update($data, $where)
    {
        if ($where) {
            return self::$db->updateFrom(self::table())->set($data)->where($where)->execute();
        }
        return false;
    }

    /**
     * 插入新数据
     *
     * @param $data
     * @return bool|int
     */
    final public static function create($data)
    {
        if ($data) {
            $lastId = self::$db->insertInto(self::table(), $data)->execute();
            return $lastId;
        }
        return false;
    }
}
