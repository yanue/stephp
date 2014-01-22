<?php

namespace Library\Core;

use Library\Fluent\FluentPDO;
use PDO;
use PDOException;

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
    public $errmsg = null;
    public $errcode = null;
    public $db = null;
    public $_pdo = null;

    /**
     * 初始化模型
     *
     * @param string $db_type
     * @param string $db_host
     * @param string $db_port
     * @param string $db_name
     * @param string $db_user
     * @param string $db_pass
     */
    public function __construct($db_type = '', $db_host = '', $db_port = '', $db_name = '', $db_user = '', $db_pass = '')
    {
        // replace default settings
        $this->db_type = $db_type;
        $this->db_host = $db_host;
        $this->db_port = $db_port;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->init();
        $this->conn();
        $this->db = new FluentPDO($this->_pdo);
    }

    public function conn()
    {
        // Set DSN
        // Set options
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names \'utf8\'',
            PDO::ATTR_PERSISTENT => true, # ?
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, #err model, ERRMODE_SILENT 0,ERRMODE_EXCEPTION 2
        );
        // Create a new PDO instanace
        try {
            $dsn = $this->db_type . ':dbname=' . $this->db_name . ';host=' . $this->db_host . ';port=' . $this->db_port;
            $this->_pdo = new PDO($dsn, $this->db_user, $this->db_pass, $options);
        } catch (PDOException $e) { // Catch any errors
            echo $this->errmsg = $e->getMessage();
            echo $this->errcode = $e->getCode();
        }
    }

    /**
     *
     */
    public function init()
    {
        $type = Config::getBase('db.type');
        $port = Config::getBase('db.port');
        $this->db_type = isset($type) ? $type : 'mysql';
        $this->db_port = isset($port) ? $port : '3306';
        $this->db_host = Config::getBase('db.host');
        $this->db_name = Config::getBase('db.name');
        $this->db_user = Config::getBase('db.user');
        $this->db_pass = Config::getBase('db.pass');
    }

    // load configs file
    public function loadConfig($file)
    {
        $file = LIB_PATH . 'configs/' . $file . '.php';
        if (file_exists($file)) {
            include_once $file;
        }
    }

    // load configs file
    public function loadModel($file)
    {
        $file = LIB_PATH . 'model/' . ucfirst($file) . 'Model.php';
        if (file_exists($file)) {
            include_once $file;
        }
    }

    public function outError($code)
    {

        $result = array(
            'error' => array('code' => $code, 'msg' => urlencode(getErrorMsg($code))),
            'data' => ''
        );
        echo urldecode(json_encode($result));
        exit;
    }

    public function sqlError()
    {
        $msg = Db::$errmsg;
        $result = array(
            'error' => array('code' => Db::$errcode, 'msg' => $msg),
            'data' => ''
        );
        echo urldecode(json_encode($result));
        exit;
    }
}
