<?php
class Loader {

    private static $_modelPath = '';
    private static $_libPath = '';
    private static $_isSetLib = false;
    private static $_isSetModel = false;
    public static $loader;

    public static function init()
    {
        if (self::$loader == NULL)
            self::$loader = new self();
        return self::$loader;
    }

    public function __construct() {
        self::$_libPath = $this->setIncludePathStr(array('./library/core','./library/db','./library/func','./library/util'));
        spl_autoload_register(array($this,'library'));
        spl_autoload_register(array($this,'model'));
    }

    /*
     * 自动加载library下面的类
     */
    public function library($class)
    {
        if(!self::$_isSetLib){
            self::$_isSetLib=true;
            // 设置所有library下的子目录
            $include_paths = get_include_path().PATH_SEPARATOR.self::$_libPath;
            set_include_path($include_paths);
        }
        $file = $class.'.php';
        require_once $file;
    }

    // TODO
    public function model($class)
    {
        if(!self::$_isSetModel){
            self::$_isSetModel=true;
            $include_paths = set_include_path(get_include_path().PATH_SEPARATOR.self::$_modelPath);
            set_include_path($include_paths);
        }
        $file = $class.'.php';
        echo 'asd';
        require_once $file;
    }

    public function setModelPath($path){
        self::$_modelPath = $path;
        spl_autoload_register(array($this,'model'));
        echo 'asd';
    }
    
    public function helper($class)
    {
        $class = preg_replace('/_helper$/ui','',$class);
        set_include_path(get_include_path().PATH_SEPARATOR.'/helper/');
        spl_autoload_extensions('.helper.php');
        spl_autoload($class);
    }

    /*
     * 组合多个目录
     *
     * 参考 set_include_path
     */
    private function setIncludePathStr($paths){
        $path_str = '';
        if(is_array($paths)){
            foreach ($paths as $path) {
                $path_str .= $path.PATH_SEPARATOR;
            }
        }else{
            $path_str = $paths;
        }
        return $path_str;
    }
}

?>