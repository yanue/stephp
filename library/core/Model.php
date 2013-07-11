<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/**
 * 数据处理模型类
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */

class Model extends Db
{
    /**
     * 初始化模型
     *
     * @param string $DB_TYPE
     * @param string $DB_HOST
     * @param string $DB_PORT
     * @param string $DB_NAME
     * @param string $DB_USER
     * @param string $DB_PASS
     */
    public function __construct($DB_TYPE='', $DB_HOST='',$DB_PORT='', $DB_NAME='', $DB_USER='', $DB_PASS=''){
        $this->init();
        if(Db::$errcode!=0){
            $this->sqlError(Db::$errmsg,Db::$errcode);
        }
    }

    /**
     *
     */
    public function init(){
        $settings = parse_ini_file(ROOT_PATH.'configs/application.ini');
        defined('DB_TYPE') || define('DB_TYPE',isset($settings['db.type'])?$settings['db.type']:'mysql');
        defined('DB_HOST') || define('DB_HOST',$settings['db.host']);
        defined('DB_PORT') || define('DB_PORT',isset($settings['db.port'])?$settings['db.type']:'3306');
        defined('DB_NAME') || define('DB_NAME',$settings['db.dbname']);
        defined('DB_USER') || define('DB_USER',$settings['db.username']);
        defined('DB_PASS') || define('DB_PASS',$settings['db.password']);
    }

    // load configs file
    public function loadConfig ($file){
        $file = ROOT_PATH.'configs/'.$file.'.php';
        if(file_exists($file)){
            include_once $file;
        }
    }

    // load configs file
    public function loadModel ($file){
        $file = ROOT_PATH.'models/'.ucfirst($file).'Model.php';
        if(file_exists($file)){
            include_once $file;
        }
    }

    public function outError($code){

        $result = array(
            'error'=>array('code'=>$code,'msg'=>urlencode(getErrorMsg($code))),
            'data'=>''
        );
        echo urldecode(json_encode($result));
        exit;
    }

    public function sqlError(){
        $msg = Db::$errmsg;
        $result = array(
            'error'=>array('code'=>Db::$errcode,'msg'=>$msg),
            'data'=>''
        );
        echo urldecode(json_encode($result));
        exit;
    }
}
