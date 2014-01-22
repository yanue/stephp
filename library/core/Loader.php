<?php
namespace Library\Core;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

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
 * modify by yanue
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
     * @param null $includePath
     * @param null $ns
     */
    public function __construct($includePath = null, $ns = null)
    {
        $this->_namespace = $ns;
        $this->_includePath = $includePath;
        // 添加到路径,01
        $this->add_include_path(realpath(LIB_PATH . '/..'));
        $this->add_include_path(WEB_ROOT);
    }

    // 添加路径
    function add_include_path($path)
    {
        foreach (func_get_args() AS $path) {
            if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir')) {
                continue;
            }

            $paths = explode(PATH_SEPARATOR, get_include_path());

            if (array_search($path, $paths) === false)
                array_push($paths, $path);

            set_include_path(implode(PATH_SEPARATOR, $paths));
        }
    }

    // 移除路径
    function remove_include_path($path)
    {
        foreach (func_get_args() AS $path) {
            $paths = explode(PATH_SEPARATOR, get_include_path());

            if (($k = array_search($path, $paths)) !== false)
                unset($paths[$k]);
            else
                continue;

            if (!count($paths)) {
                continue;
            }

            set_include_path(implode(PATH_SEPARATOR, $paths));
        }
    }

    /**
     * Installs this fdfs loader on the SPL autoload stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Loads the given fdfs or interface.
     * --针对WEB_ROOT,项目根路径下查找并加载文件
     * --针对LIB_PATH,当前类库下查找并加载文件
     *   LIB_PATH为类库的根,目录名称为library(不能改变).
     *
     * @param string $className The name of the fdfs to load.
     * @return void
     */
    public function loadClass($className)
    {
        if (null === $this->_namespace || $this->_namespace . $this->_namespaceSeparator === substr($className, 0, strlen($this->_namespace . $this->_namespaceSeparator))) {

            $namespace_path = '';
            if (false !== ($lastNsPos = strripos($className, $this->_namespaceSeparator))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $namespace_path = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }

            // 由命名空间转换而来的文件路径
            $file_path = strtolower($namespace_path);
            $fileClass = $file_path . $className;
            $file = $fileClass . $this->_fileExtension;

            // 处于核心类库则加载核心类库
            // LIB_PATH为类库的根,目录名称为library(不能改变)
            if (in_array($file_path, array('library/core/', 'library/util/', 'library/db/'))) {
                $lib_file = realpath(LIB_PATH . '/../' . $file);
                if (file_exists($lib_file)) {
                    require_once $lib_file;
                }
            } else {
                // 这里加载其他类(如数据操作模型等)
                $class = realpath(WEB_ROOT . '/' . $file);
                if (file_exists($class)) {
                    include_once $class;
                }
            }
        }
    }

}