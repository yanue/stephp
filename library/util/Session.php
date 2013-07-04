<?php

if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/**
 * session 处理库
 * @example     Session::instance()->get("key")
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.1 - 2013-07-02
 */

//使用 COOKIE 保存 SESSION ID 的方法
ini_set('session.use_cookies',1);

class Session
{
    private $sessionState = false;
    protected $session_cache_expire = 300;

    /**
     * 开启session
     *
     * @return boolean
     */
    function __construct() {
        session_cache_expire($this->session_cache_expire);
        if( session_id() == '' )
        {
            $this->sessionState = session_start();
            session_set_cookie_params(time()+3600, '/', '/', NULL, NULL);
        }
        return $this->sessionState;
    }

    /*
     * 设置session过期时间
     *
     * @param $time 时间长度(秒)
     */
    public function setExpire($time){
        if($time){
            session_cache_expire($time);
        }
    }


    /**
     * 实例化对象
     *
     * @return  object
     */

    public static function instance(){
        static $obj;
        if(!$obj) $obj =  new self();
        return $obj;
    }


    /**
     * set 设置变量
     *
     * @param string $name
     * @param string|int|array $value
     * @return void
     */

    public function set( $name , $value ){
        $_SESSION[$name] = $value;
    }

    /**
     * get 获取变量
     *
     * @param string  $name
     * @return sring|int|array
     */

    public function get( $name, $default = '' )
    {
        return (isset($_SESSION[$name]) ? $_SESSION[$name] : $default);
    }

    /**
     * 获取全部session
     *
     * @return array
     */

    public function getAll()
    {
        return $_SESSION;
    }

    /**
     * 判断session是否为空
     *
     * @param string $name
     * @return bool
     */
    public function isEmpty($name)
    {
        return empty($_SESSION[$name]);
    }

    /**
     * 判断key 是否存在
     *
     * @param string $name
     * @return boolean
     */
    public function exists( $name )
    {
        return isset($_SESSION[$name]);
    }

    /**
     * 删除session
     *
     * @param string $name
     */
    public function del( $name )
    {
        unset( $_SESSION[$name] );
    }


    /**
     * 清楚session
     *
     * @return boolean
     */

    public function destroy()
    {
        if ($this->sessionState)
        {
            $this->sessionState = !session_destroy();
            unset($_SESSION );
            return true;
        }
        return FALSE;
    }
}