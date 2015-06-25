<?php
namespace Library\Cache\Driver;

use Library\Cache\CacheAbstract;
use Library\Core\Config;

/**
 * File Caching Driver
 *
 */
class CacheFile extends CacheAbstract
{
    /**
     * Directory in which to save cache files
     *
     * @var string
     */
    protected $_cache_path;

    public function __construct()
    {
        $path = Config::getItem('cache.filePath', 'cache');
        $this->_cache_path = !$path ? WEB_ROOT . '/cache/file/' : $path;
    }

    /**
     */
    public function init()
    {
        //nothing to do
        return true;
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch from cache
     * @param int|string $keyName
     * @return bool
     */
    public function get($keyName)
    {
        if (!file_exists($this->_cache_path . $keyName)) {
            return FALSE;
        }

        $data = unserialize(file_get_contents($this->_cache_path . $keyName));

        if ($data['ttl'] > 0 && time() > $data['time'] + $data['ttl']) {
            unlink($this->_cache_path . $keyName);
            return FALSE;
        }

        return $data['data'];
    }

    /**
     * save
     * @param null $keyName
     * @param null $content
     * @param $lifetime
     * @return bool
     */
    public function save($keyName = null, $content = null, $lifetime = 86400)
    {
        $contents = array(
            'time' => time(),
            'ttl' => $lifetime,
            'data' => $content
        );

        if (file_put_contents($this->_cache_path . $keyName, serialize($contents))) {
            @chmod($this->_cache_path . $keyName, 0660);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Delete from Cache
     *
     * @param int|string $keyName
     * @return bool
     */
    public function delete($keyName)
    {
        return file_exists($this->_cache_path . $keyName) ? unlink($this->_cache_path . $keyName) : FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the Cache
     *
     * @return    bool    false on failure/true on success
     */
    public function clean()
    {
        return unlink($this->_cache_path, true);
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     *
     * Not supported by file-based caching
     *
     * @param    string    user/filehits
     * @return    mixed    FALSE
     */
    public function cacheInfo($type = NULL)
    {
        return array(
            fileatime($this->_cache_path),
            filesize($this->_cache_path),
            filectime($this->_cache_path),
            fileowner($this->_cache_path),
            filetype($this->_cache_path)
        );
    }

    /**
     * Get Cache Metadata
     *
     * @param $keyName
     * @return array|bool
     */
    public function getMetaData($keyName)
    {
        if (!file_exists($this->_cache_path . $keyName)) {
            return FALSE;
        }

        $data = unserialize(file_get_contents($this->_cache_path . $keyName));

        if (is_array($data)) {
            $mtime = filemtime($this->_cache_path . $keyName);

            if (!isset($data['ttl'])) {
                return FALSE;
            }

            return array(
                'expire' => $mtime + $data['ttl'],
                'mtime' => $mtime
            );
        }

        return FALSE;
    }

    /**
     * Is supported
     *
     * In the file driver, check to see that the cache directory is indeed writable
     *
     * @return bool
     */
    public function isSupported()
    {
        return is_writable($this->_cache_path);
    }

}