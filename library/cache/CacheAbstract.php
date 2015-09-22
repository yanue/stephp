<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 6/25/15
 * Time: 3:02 PM
 */

namespace Library\Cache;


use Library\Core\Exception;

abstract class CacheAbstract
{
    /**
     * Returns a cached content
     *
     * @param int|string $keyName
     * @return mixed
     */
    abstract public function get($keyName);


    /**
     * Stores cached content into the file backend and stops the frontend
     *
     * @param int|string $keyName
     * @param string $content
     * @param $lifetime
     * @return boolean
     */
    abstract public function save($keyName = null, $content = null, $lifetime = 86400);


    /**
     * Deletes a value from the cache by its key
     *
     * @param int|string $keyName
     * @return boolean
     */
    abstract public function delete($keyName);


    /**
     * Checks if cache  and it hasn't expired
     *
     * @param  string $keyName
     * @return boolean
     */
    abstract public function exists($keyName);


    /**
     * Immediately invalidates all existing items.
     *
     * @return boolean
     */
    abstract public function flush();


    /**
     * Array of drivers that are available to use with the driver class
     *
     * @var array
     */
    protected $validDrivers = array();

    /**
     * Name of the current class - usually the driver class
     *
     * @var string
     */
    protected $libPath;

    /**
     * Get magic method
     *
     * The first time a child is used it won't exist, so we instantiate it
     * subsequents calls will go straight to the proper child.
     *
     * @param
     *            string    Child class name
     * @return object class
     */
    public function __get($child)
    {
        // Try to load the driver
        return $this->loadDriver($child);
    }

    /**
     * Load driver
     * --Separate load_driver call to support explicit driver load by library or user
     *
     * @param $driver
     * @return object
     * @throws Exception
     */
    public function loadDriver($driver)
    {
        // Get CodeIgniter instance and subclass prefix
        // $prefix = config_item('cache.driver.class.prefix');
        $prefix = 'Cache';
        if (!isset ($this->libPath)) {
            // Get library name without any prefix
            $this->libPath = rtrim(get_class($this), $prefix) . 'Driver\\';
        }
        // spl autoload
        $_namespaceClass = '\Library\Cache\Driver\\Cache' . ucfirst($driver);

        // See if requested child is a valid driver
        if (!in_array($driver, $this->validDrivers)) {
            // The requested driver isn't valid!
            $msg = 'Invalid driver requested: ' . $_namespaceClass;
            throw new Exception ($msg);
        }

        // Did we finally find the class?
        if (class_exists($_namespaceClass, true)) {
            // Instantiate, decorate and add child
            $obj = new $_namespaceClass ();
//            $obj->decorate($this);
            $this->$driver = $obj;
        } else {
            $msg = 'Unable to load the requested driver: ' . $_namespaceClass;
            throw new Exception ($msg);
        }

        return $this->$driver;
    }
}