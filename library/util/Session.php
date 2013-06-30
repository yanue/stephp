<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/**
 * session库
 * @example     Session::getInstance()->get("key")
 * @copyright	http://www.yanue.net
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0 - 2012-07-02
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
            session_set_cookie_params(time()+3600, '/', '/', NULL, NULL);
            $this->sessionState = session_start();
        }
        return $this->sessionState;
    }


    /**
     *    实例化对象
     *
     *    @return  object
     **/

    public static function instance(){
        static $obj;
        if(!$obj) $obj =  new self();
        return $obj;
    }


    /**
     * set 变量
     * @param string $name
     * @param string|int|array $value
     * @return void
     */

    public function set( $name , $value ){
        $_SESSION[$name] = $value;
    }

    /**
     * 获取变量
     * @param string  $name
     * @return sring|int|array
     */

    public function get( $name, $default = '' )
    {
        return (isset($_SESSION[$name]) ? $_SESSION[$name] : $default);
    }

    /**
     * 获取全部session
     * @param string  $name
     * @return array
     */

    public function getAll()
    {
        return $_SESSION;
    }

    /**
     * Returns true if there no session with this name or it's empty, or 0,
     * or a few other things. Check http://php.net/empty for a full list.
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
     * @param string $name
     * @return boolean
     */
    public function exists( $name )
    {
        return isset($_SESSION[$name]);
    }

    /**
     * 删除
     * @param string $name
     */
    public function del( $name )
    {
        unset( $_SESSION[$name] );
    }


    /**
     *    清楚session
     *
     *    @return    boolean
     **/

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