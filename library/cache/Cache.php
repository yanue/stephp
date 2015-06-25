<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 6/25/15
 * Time: 3:04 PM
 */

namespace library\cache;


use Library\Core\Config;

class Cache extends CacheAbstract
{
    /**
     * @var Cache
     */
    private static $instance = null;

    /**
     * Valid cache drivers
     *
     * @var array
     */
    protected $validDrivers = array(
        'apc',
        'file',
        'memcached',
        'redis',
    );

    /**
     * Path of cache files (if file-based cache)
     *
     * @var string
     */
    protected $_cachePath = NULL;

    /**
     * Reference to the driver
     *
     * @var mixed
     */
    protected $_adapter = 'file';

    /**
     * Fallback driver
     *
     * @var string
     */
    protected $_backupDriver = 'file';

    /**
     * Cache key prefix
     *
     * @var string
     */
    public $keyPrefix = '';

    /**
     * Name of the current class - usually the driver class
     *
     * @var string
     */
    protected $libName;

    /**
     * @param string $adapter
     */
    public function __construct($adapter = '')
    {
        $config = Config::load('cache');
        $this->_adapter = $adapter ? $adapter : $config['cache.defaultDriver'];
        isset ($config ['cache.keyPrefix']) && $this->keyPrefix = $config ['cache.keyPrefix'];

        if (isset ($config ['backup']) && in_array($config ['backup'], $this->validDrivers)) {
            $this->_backupDriver = $config ['backup'];
        }

        // If the specified adapter isn't available, check the backup.
        if (!$this->isSupported($this->_adapter)) {
            if (!$this->isSupported($this->_backupDriver)) {
                // Backup isn't supported either. Default to 'Dummy' driver.
                $this->_adapter = 'file';
            } else {
                // Backup is supported. Set it to primary.
                $this->_adapter = $this->_backupDriver;
            }
        }

        $this->{$this->_adapter}->init();
    }

    /**
     * Initialize class properties based on the configuration array.
     *
     * @param string $adapter
     * @return Cache
     */
    public static function getInstance($adapter = '')
    {
        if (!self::$instance instanceof Cache) {
            self::$instance = new self($adapter);
        }
        return self::$instance;
    }

    /**
     * Get magic method
     * The first time a driver is used it won't exist, so we instantiate it
     * subsequents calls will go straight to the proper driver.
     *
     * @param $driver
     * @return object
     * @throws \Library\Core\Exception
     */
    public function __get($driver)
    {
        // Try to load the driver
        return $this->loadDriver($driver);
    }

    /**
     * Get
     *
     * Look for a value in the cache. If it exists, return the data
     * if not, return FALSE
     *
     * @param string $keyName
     * @return mixed matching $id or FALSE on failure
     */
    public function get($keyName)
    {
        $data = $this->{$this->_adapter}->get($this->keyPrefix . md5($keyName));
        return $data ? unserialize($data) : "";
    }

    /**
     * Stores cached content into the file backend and stops the frontend
     *
     * @param int|string $keyName
     * @param string $content
     * @param $lifetime
     * @return boolean
     */
    public function save($keyName = null, $content = null, $lifetime = 86400)
    {
        return $this->{$this->_adapter}->save($this->keyPrefix . md5($keyName), serialize($content), $lifetime);
    }

    /**
     * Delete from Cache
     *
     * @param string $key
     * @return bool on success, FALSE on failure
     */
    public function delete($key)
    {
        return $this->{$this->_adapter}->delete($this->keyPrefix . md5($key));
    }

    /**
     * Clean the cache
     *
     * @return bool on success, FALSE on failure
     */
    public function clean()
    {
        return $this->{$this->_adapter}->clean();
    }

    /**
     * Cache Info
     *
     * @param string $type
     * @return mixed containing cache info on success OR FALSE on failure
     */
    public function cacheInfo($type = 'user')
    {
        return $this->{$this->_adapter}->cacheInfo($type);
    }

    /**
     * Get Cache Metadata
     *
     * @param $key
     * @return mixed item metadata
     */
    public function getMetaData($key)
    {
        return $this->{$this->_adapter}->getMetaData($this->keyPrefix . md5($key));
    }

    /**
     * Is the requested driver supported in this environment?
     *
     * @param string $driver
     *            to test
     * @return array
     */
    public function isSupported($driver)
    {
        static $support = array();

        if (!isset ($support[$driver])) {
            $support[$driver] = $this->{$driver}->isSupported();
        }

        return $support[$driver];
    }
}