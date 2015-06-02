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
class Model
{
    private static $instance = null;
    /**
     * @var FluentPDO
     */
    private static $db = null;

    public static function connect()
    {
        $dbType = Config::getSite('database', 'db.defaultSqlDriver');
        if (empty($dbType)) {
            $dbType = 'mysql';
        }
        $config = Config::getSite('database', 'db.drivers.' . $dbType);

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
        return $fpdo;
    }

    public function __construct()
    {

    }

    public static function findFirst($parameters = null)
    {
        return self::$db->from('user')->fetch();
    }

    public static function getInstance()
    {
        if (!self::$instance) {

        }

        return self::$instance;
    }

    public static function find($parameters = null)
    {

    }

    public static function model()
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

    /**  PHP 5.3.0之后版本  */
    public static function __callStatic($name, $arguments)
    {
        // 注意: $name 的值区分大小写
        echo "Calling static method '$name' "
            . implode(', ', $arguments) . "\n";
    }

    final  public function save($data = null, $whiteList = null)
    {
    }

    final public function create($data = null, $whiteList = null)
    {
    }
}
