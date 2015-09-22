<?php

namespace Library\Cache\Driver;

use Library\Cache\CacheAbstract;
use Library\Core\Config;
use Library\Core\Exception;

/**
 * Redis Caching Driver
 */
class CacheRedis extends CacheAbstract
{
    /**
     * Default config
     *
     * @var array
     */
    protected static $_default_config = array(
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => 6379,
        'timeout' => 0
    );

    /**
     * Redis connection
     *
     * @var \Redis
     */
    protected $_redis;

    /**
     * @param int|string $keyName
     * @return mixed|string
     */
    public function get($keyName)
    {
        return $this->_redis->get($keyName);
    }

    /**
     * Checks if cache exists and it hasn't expired
     *
     * @param  string $keyName
     * @return boolean
     */
    public function exists($keyName)
    {
        return $this->_redis->exists($keyName);

    }

    /**
     * Save cache
     *
     * @param null $keyName
     * @param null $content
     * @param int $lifetime
     * @return bool
     */
    public function save($keyName = null, $content = null, $lifetime = null)
    {
        if (!$keyName) {
            return false;
        }
        return ($lifetime) ? $this->_redis->setex($keyName, $lifetime, $content) : $this->_redis->set($keyName, $content);
    }

    /**
     * Delete from cache
     *
     * @param int|string $keyName
     * @return bool
     */
    public function delete($keyName)
    {
        return ($this->_redis->delete($keyName) === 1);
    }

    /**
     * Clean cache
     *
     * @return bool
     * @see Redis::flushDB()
     */
    public function flush()
    {
        return $this->_redis->flushDB();
    }

    /**
     * Get cache driver info
     * @param string $type Not supported in Redis.
     * @return array
     * @see Redis::info()
     */
    public function cacheInfo($type = NULL)
    {
        return $this->_redis->info();
    }

    /**
     * Get cache metadata
     *
     * @param
     *            string    Cache key
     * @return array
     */
    public function getMetaData($key)
    {
        $value = $this->get($key);

        if ($value) {
            return array(
                'expire' => time() + $this->_redis->ttl($key),
                'data' => $value
            );
        }

        return FALSE;
    }

    /**
     * Check if Redis driver is supported
     *
     * @return bool
     */
    public function isSupported()
    {
        if (extension_loaded('redis')) {
            return TRUE;
        } else {
            // log_message('debug', 'The Redis extension must be loaded to use Redis cache.');
            return FALSE;
        }
    }

    /**
     * Setup Redis config and connection
     *
     * @throws Exception
     * @return bool
     * @see Redis::connect()
     */
    public function init()
    {
        $config = Config::getItem('cache.drivers.redis');
        if (is_array($config)) {
            $config = array_merge(self::$_default_config, $config);
        } else {
            $config = self::$_default_config;
        }
        $this->_redis = new \Redis ();

        try {
            $this->_redis->connect($config ['host'], $config ['port'], $config ['timeout']);
        } catch (\RedisException $e) {
            throw new Exception ('Redis connection refused. ' . $e->getMessage());
        }

        if (isset ($config ['password'])) {
            $this->_redis->auth($config ['password']);
        }

        return $this->_redis;
    }

    /**
     * Class destructor
     *
     * Closes the connection to Redis if present.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->_redis) {
            $this->_redis->close();
        }
    }
}