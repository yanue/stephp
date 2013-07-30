<?php
namespace Library\Core;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * SplClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 *
 *     // Example which loads classes for the Doctrine Common package in the
 *     // Doctrine\Common namespace.
 *     $classLoader = new SplClassLoader('Doctrine\Common', '/path/to/doctrine');
 *     $classLoader->register();
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class Loader
{
    private $_fileExtension = '.php';
    private $_namespace;
    private $_includePath;
    private $_namespaceSeparator = '\\';

    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     *
     * @param string $ns The namespace to use.
     */
    public function __construct($includePath = null,$ns = null)
    {
        $this->_namespace = $ns;
        $this->_includePath = $includePath;
        // 添加到路径,便于多目录引用
        $this->add_include_path(realpath(LIB_PATH.'/..'));
        $this->add_include_path(WEB_ROOT);
    }

    function add_include_path ($path)
    {
        foreach (func_get_args() AS $path)
        {
            if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir'))
            {
                continue;
            }

            $paths = explode(PATH_SEPARATOR, get_include_path());

            if (array_search($path, $paths) === false)
                array_push($paths, $path);

            set_include_path(implode(PATH_SEPARATOR, $paths));
        }
    }

    function remove_include_path ($path)
    {
        foreach (func_get_args() AS $path)
        {
            $paths = explode(PATH_SEPARATOR, get_include_path());

            if (($k = array_search($path, $paths)) !== false)
                unset($paths[$k]);
            else
                continue;

            if (!count($paths))
            {
                continue;
            }

            set_include_path(implode(PATH_SEPARATOR, $paths));
        }
    }

    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     *
     * @param string $sep The separator to use.
     */
    public function setNamespaceSeparator($sep)
    {
        $this->_namespaceSeparator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return void
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }

    /**
     * Sets the base include path for all class files in the namespace of this class loader.
     *
     * @param string $includePath
     */
    public function setIncludePath($includePath)
    {
        $this->_includePath = $includePath;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath
     */
    public function getIncludePath()
    {
        return $this->_includePath;
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     *
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->_fileExtension = $fileExtension;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public function getFileExtension()
    {
        return $this->_fileExtension;
    }

    /**
     * Installs this class loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    /**
     * Loads the given class or interface.
     * --针对WEB_ROOT,项目根路径下查找并加载文件
     * --针对LIB_PATH,当前类库下查找并加载文件
     *   LIB_PATH为类库的根,目录名称为library(不能改变).
     *
     * @param string $className The name of the class to load.
     * @return void
     */
    public function loadClass($className)
    {
        if (null === $this->_namespace || $this->_namespace.$this->_namespaceSeparator === substr($className, 0, strlen($this->_namespace.$this->_namespaceSeparator))) {

            $fileName = '';
            if (false !== ($lastNsPos = strripos($className, $this->_namespaceSeparator))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }

            $fileClass = strtolower($fileName) . str_replace('_', DIRECTORY_SEPARATOR, $className);
            $file =  $fileClass . $this->_fileExtension;
            // LIB_PATH为类库的根,目录名称为library(不能改变)
            if(file_exists(WEB_ROOT.'/'.$file) || file_exists(LIB_PATH.'/../'.$file)){
                include_once $file;
            }
        }
    }

    /**
     * 获取(单个)系统配置信息
     *
     * @param string $key 具体需要获取的键名
     * @return mixed
     */
    public static function getConfig($key=''){
        $settings = parse_ini_file(WEB_ROOT.'/config/application.ini');
        if (!$key ){ return $settings; }
        return isset($settings[$key]) ? $settings[$key] : '' ;
    }
}