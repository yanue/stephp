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
abstract class Model extends FluentPDO
{
    private static $pdo = null;

    public function __construct()
    {
        if (!self::$pdo instanceof \PDO) {
            $dbType = Config::getSite('database', 'db.defaultSqlDriver');
            if (empty($dbType)) {
                $dbType = 'mysql';
            }
            $config = Config::getSite('database', 'db.drivers.' . $dbType);

            $db_port = isset($config['port']) ? $config['port'] : '3306';
            $db_host = $config['host'];
            $db_name = isset($this->database) && !empty($this->database) ? $this->database : $config['name'];
            $db_user = $config['user'];
            $db_pass = $config['pass'];

            $options = array(
// 	            \PDO::MYSQL_ATTR_INIT_COMMAND    => 'set names \'utf8\'',
                \PDO::ATTR_PERSISTENT => false, # ?
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, #err model, ERRMODE_SILENT 0,ERRMODE_EXCEPTION 2
            );
            // Create a new PDO instanace
            try {
                $dsn = $dbType . ':dbname=' . $db_name . ';host=' . $db_host . ';port=' . $db_port;
                self::$pdo = new \PDO($dsn, $db_user, $db_pass, $options);
                self::$pdo->exec('set names \'utf8\'');
            } catch (\PDOException $e) { // Catch any errors
                self::$pdo = null;
                $this->errmsg = $e->getMessage();
                $this->errcode = $e->getCode();
                Debug::log($e->getFile() . ' line ' . $e->getLine() . ":" . $e->getMessage() . "\n\t\tTrace:" . $e->getTraceAsString(), 'ERROR');
            }
        }

        if (empty($this->table)) {
            $reflection = new \ReflectionClass($this);
            $className = $reflection->getShortName();
            $reflection = new \ReflectionClass($this);
            $modelName = $reflection->getShortName();
            $table = substr($modelName, 0, -5);
            preg_match_all('/((?:^|[A-Z])[a-z]+)/', $table, $matches);
            $this->table = strtolower(implode('_', $matches[0]));
            if ($this->tableQuantity <= 1) {
                $this->trueTable = $this->table;
            }
            unset($modelName);
            unset($reflection);
            unset($table);
            unset($matches);
        }

        if ($this->tableQuantity > 0 && empty($this->splitColumn)) {
            $this->splitColumn = $this->primaryKey;
        }
        print_r(self::$pdo);
        return self::$pdo;
    }


    public static function findFirst($parameters = null)
    {
        echo 222;
        print_r($parameters);

    }

    public static function find($parameters = null)
    {
    }

    public static function fetchOne($parameters = null)
    {
    }

    public static function fetchAll($parameters = null)
    {
    }

    public static function query($dependencyInjector = null)
    {
    }

    public static function count($parameters = null)
    {
    }

    public static function sum($parameters = null)
    {
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

    final  public function save($data = null, $whiteList = null)
    {
    }

    final public function create($data = null, $whiteList = null)
    {
    }

}
