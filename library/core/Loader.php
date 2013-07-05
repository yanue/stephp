<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/**
 *
 * 类库自动加载器 -  Loader.php
 * --外接口
 *  -addIncludePath() 追加目录到include_path
 *  -getConfig() 获取(单个)系统配置信息
 *
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.1 - 13-7-4
 */

class Loader {

    /*
     * 初始化
     *
     */
    public function __construct() {
        $include_paths = array('./library/core','./library/db','./library/func','./library/util');
        $this->setIncludePath($include_paths);
        spl_autoload_register(array($this,'loadClass'));
    }

    /*
     * 追加目录到include_path
     *
     */
    public function addIncludePath($path){
        echo $path;
        $this->setIncludePath($path);
    }

    /*
     * 获取(单个)系统配置信息
     *
     * @param string $key 具体需要获取的键名
     * @return mixed
     */
    public static function getConfig($key=''){
        $settings = parse_ini_file(ROOT_PATH.'configs/application.ini');
        if (!$key ){ return $settings; }
        return isset($settings[$key]) ? $settings[$key] : '' ;
    }

    /*
     * 自动加载library下面的类
     *
     */
    private function loadClass($class){
        # 文件名就是类名
        $file = $class.'.php';
        include_once $file;
    }

    /*
     * 设置新目录并合并添加到include_path
     *
     * @return bool
     */
    private function setIncludePath($paths){
        if( !$paths ){ return false; }
        # 原始路径
        $old_paths = get_include_path();
        $old_paths_arr =  explode(PATH_SEPARATOR,$old_paths);
        # 要添加的路径
        $new_paths = is_array($paths) ? $paths : array($paths);
        # 合并,保持唯一,生成字串
        $now_paths = array_unique(array_merge($old_paths_arr , $new_paths));
        $include_paths = implode(PATH_SEPARATOR,$now_paths);
        # 设置到include_path
        set_include_path($include_paths);
        return true;
    }
}