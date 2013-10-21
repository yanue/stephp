<?php
namespace Library\Db;
use PDOException;
use PDO;
if ( ! defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 数据库操作处理 - Db.php
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/db
 * @time     2013-07-11
 */
class Db {
    private static $db = null;
    public static $errmsg;
    public static $errcode;

    public function __construct($DB_TYPE, $DB_HOST,$DB_PORT, $DB_NAME, $DB_USER, $DB_PASS){
        // Set DSN
        // Set options
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names \'utf8\'',
            PDO::ATTR_PERSISTENT            => true,# ?
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION ,#err model, ERRMODE_SILENT 0,ERRMODE_EXCEPTION 2
        );
        // Create a new PDO instanace
        try{
            $dsn = $DB_TYPE.':dbname='.$DB_NAME.';host='.$DB_HOST.';port='.$DB_PORT;
            self::$db = new PDO($dsn,$DB_USER,$DB_PASS,$options);
        } catch(PDOException $e){ // Catch any errors
            self::$errmsg = $e->getMessage();
            self::$errcode = $e->getCode();
        }
    }

    /**
     * select
     * param string $sql An SQL string
     * param array $array Paramters to bind
     * param constant $fetchMode A PDO Fetch mode
     * param constant $filter_func a filter function
     * return mixed
     */
    public static function select($sql, $array = array(),$filter_func='', $fetchMode = PDO::FETCH_ASSOC)
    {
       $sth = self::$db->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }
        $sth->execute();
        $res = null;
        if($filter_func){
            while ($row = $sth->fetch($fetchMode)) {
                $res[] = call_user_func_array($filter_func,array(&$row));
            }
        }else{
            $res = $sth->fetchAll($fetchMode);
        }
        unset($sth);
        return $res;
    }

    /**
     * select
     * param string $sql An SQL string
     * param array $array Paramters to bind
     * param constant $fetchMode A PDO Fetch mode
     * param constant $filter_func a filter function
     * return mixed
     */
    public static function selectOne($sql, $array = array(),$filter_func='', $fetchMode = PDO::FETCH_ASSOC)
    {
        $sth = self::$db->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }
        $sth->execute();

        $res = $sth->fetch($fetchMode);

        unset($sth);
        return $res;
    }


    /**
     * insert
     * param string $table A name of table to insert into
     * param string $data An associative array
     */
    public static function insert($table, $data,$ignore=false)
    {
        ksort($data);
        $ignore = $ignore ? ' IGNORE ' : ' ';
        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        $sql = "INSERT ".$ignore." INTO $table (`$fieldNames`) VALUES ($fieldValues)";

        $sth = self::$db->prepare($sql);

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        $sth->execute();
        return self::$db->lastInsertId();
    }

    /**
     * update
     * param string $table A name of table to insert into
     * param string $data An associative array
     * param string $where the WHERE query part
     */
    public static function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = NULL;
        foreach($data as $key=> $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

         $sth = self::$db->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        //var_dump($sth);

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $res = $sth->execute();
        unset($sth);
        return $res;
    }

    /**
     * delete
     *
     * param string $table
     * param string $where
     * param integer $limit
     * return integer Affected Rows
     */
    public static function delete($table, $where, $limitCount = 0)
    {
        if($limitCount>0){
            $limit = ' LIMIT '.$limitCount;
        }else{
            $limit = '';
        }
        $sql = "DELETE FROM $table WHERE $where ".$limit;
        return self::$db->exec($sql);
    }

    public static function beginTransaction(){
        return self::$db->beginTransaction();
    }

    public static function exec($sql=''){
        return self::$db->exec($sql);
    }

    public static function commit(){
        return self::$db->commit();
    }

    public static function rollBack(){
        return self::$db->rollBack();
    }

    public static function setAttribute($attr,$val){
        return self::$db->setAttribute ($attr,$val);
    }

    public static function affectedRows(){
        return self::$db;
    }

    public static function lastInsertId(){
        return self::$db->lastInsertId();
    }

    # Transaction
    public static function transaction($sqlQueue='')
    {
        //$this->connection();
        if(count($sqlQueue)>0)
        {
            /**
             * Manual says:
             * If you do not fetch all of the data in a result set before issuing your next call to PDO::query(), your call may fail. Call PDOStatement::closeCursor() to release the database resources associated with the PDOStatement object before issuing your next call to PDO::query().
             * */
            //self::$db->closeCursor();
            //关闭自动提交
            self::$db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
            try
            {

                self::$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                self::$db->beginTransaction();
                foreach ($sqlQueue as $sql)
                {
                    self::$db->exec($sql);
                }
                return self::$db->commit();
            } catch (Exception $e) {
                self::$errmsg = $e->getMessage().' in SQL :'.$sql;
                self::$errcode = $e->getCode();
                self::$db->rollBack();
                return false;
            }
            // 重新开启自动提交
            self::$db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        }
        return false;
    }

    /**
     * 生成sql insert语句中的values (p1, q1...), (p2, q2...)... 部分
     * param $tags 字段二维数组
     * param $keys  按k的顺序生成，否则按kv默认生成
     */
    public static function makeValueS($tags, $keys = null) {
        $groups = array();
        if ($keys) {
            foreach ($tags as $tag) {
                $value = array();
                foreach($keys as $k) {
                    $k = trim($k, '`');
                    $value[] = (is_string($tag[$k]) ? ('\''.addslashes($tag[$k]).'\'') : $tag[$k]);
                }
                $groups[] = '('.implode(' , ', $value).')';
            }
        }
        else {
            foreach ($tags as $tag) {
                $value = array();
                foreach($tag as $v) {
                    $value[] = (is_string($v) ? ('\''.addslashes($v).'\'') : $v);
                }
                $groups[] = '('.implode(' , ', $value).')';
            }
        }

        return implode(' , ', $groups);
    }

}