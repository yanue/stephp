<?php

namespace Library\Cache\Driver;

use Library\Cache\CacheAbstract;
use Library\Core\Config;
use Library\Core\Debug;
use Library\Core\Exception;

/**
 * Memcached Caching Driver
 *
 */
class CacheMemcached extends CacheAbstract
{
    /**
     * Holds the memcached object
     *
     * @var \Memcached
     */
    protected $_memcached;

    /**
     * Memcached configuration
     *
     * @var array
     */
    protected $_memcacheServers = array(
        'default' => array(
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 1
        )
    );

    /**
     * Fetch from cache
     * @param int|string $keyName
     * @return bool
     */
    public function get($keyName)
    {
        try {
            $data = $this->_memcached->get($keyName);
        } catch (Exception $e) {
            Debug::log($e->getFile() . ":" . $e->getMessage());
            Debug::log($e->getTraceAsString());
            return false;
        }

        return is_array($data) ? $data [0] : false;
    }

    /**
     * Checks if cache exists and it hasn't expired
     *
     * @param  string $keyName
     * @return boolean
     */
    public function exists($keyName)
    {
        try {
            $data = $this->_memcached->get($keyName);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * save
     * @param null $keyName
     * @param null $content
     * @param $lifetime
     * @return bool
     */
    public function save($keyName = null, $content = null, $lifetime = 0)
    {
        $data = array($content, time(), $lifetime);
        if (get_class($this->_memcached) === 'Memcached') {
            return $this->_memcached->set($keyName, $data, $lifetime);
        } elseif (get_class($this->_memcached) === 'Memcache') {
            return $this->_memcached->set($keyName, $data, 0, $lifetime);
        }

        return FALSE;
    }

    /**
     * Delete from Cache
     *
     * @param $keyName
     * @return bool on success, false on failure
     */
    public function delete($keyName)
    {
        return $this->_memcached->delete($keyName);
    }

    /**
     * Clean the Cache
     *
     * @return bool on failure/true on success
     */
    public function flush()
    {
        return $this->_memcached->flush();
    }

    /**
     * Cache Info
     *
     * @return mixed on success, false on failure
     */
    public function cacheInfo()
    {
        return $this->_memcached->getStats();
    }

    /**
     * Get Cache Metadata
     *
     * @param
     *            mixed    key to get cache metadata on
     * @return mixed on failure, array on success.
     */
    public function getMetaData($key)
    {
        $stored = $this->_memcached->get($key);

        if (count($stored) !== 3) {
            return FALSE;
        }

        list ($data, $time, $ttl) = $stored;

        return array(
            'expire' => $time + $ttl,
            'mtime' => $time,
            'data' => $data
        );
    }

    /**
     * implements the distribute servers setting.
     *
     * @see \Library\Cache\Driver::init()
     */
    public function init()
    {
        // Try to load memcached server info from the config file.
        $this->_memcacheServers = Config::getSite('cache', 'cache.drivers.memcached');

        if (class_exists('Memcached', FALSE)) {
            $this->_memcached = new \Memcached ();
            $this->_memcached->setOptions(array(
                //        	\Memcached::OPT_COMPRESSION => FALSE,
                //          \Memcached::OPT_SERIALIZER => \Memcached::SERIALIZER_IGBINARY
            ));
        } elseif (class_exists('Memcache', FALSE)) {
            $this->_memcached = new \Memcache ();
        } else {
            // log_message('error', 'Failed to create object for Memcached Cache; extension not loaded?');
            throw new Exception ('Failed to create object for Memcached Cache; extension not loaded?');
        }

        foreach ($this->_memcacheServers as $cacheServer) {
            if (!isset ($cacheServer ['host'])) {
                continue;
            }
            if (empty ($cacheServer ['port'])) {
                $cacheServer ['port'] = 11211;
            }
            if (empty ($cacheServer ['weight'])) {
                $cacheServer ['weight'] = 1;
            }

            if (get_class($this->_memcached) === 'Memcache') {
                // Third parameter is persistance and defaults to TRUE.
                $this->_memcached->addServer($cacheServer ['host'], $cacheServer ['port'], TRUE, $cacheServer ['weight']);
            } else {
                $this->_memcached->addServer($cacheServer ['host'], $cacheServer ['port'], $cacheServer ['weight']);
            }
        }

        return TRUE;
    }

    /**
     * Is supported
     *
     * Returns FALSE if memcached is not supported on the system.
     * If it is, we setup the memcached object & return TRUE
     *
     * @return bool
     */
    public function isSupported()
    {
        if (!extension_loaded('memcached') && !extension_loaded('memcache')) {
            // log_message('debug', 'The Memcached Extension must be loaded to use Memcached Cache.');
            return FALSE;
        }

        return TRUE;
    }
}