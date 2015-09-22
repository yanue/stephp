<?php
namespace Library\Cache\Driver;

use Library\Cache\CacheAbstract;
use Library\Cache\Driver;

/**
 * Apc Caching Driver
 */
class CacheApc extends CacheAbstract
{

    public function init()
    {
        //nothing to do
        return true;
    }

    /**
     * Get
     *
     * Look for a value in the cache. If it exists, return the data
     * if not, return FALSE
     *
     * @param    string
     * @return    mixed    value that is stored/FALSE on failure
     */
    public function get($id)
    {
        $success = FALSE;
        $data = apc_fetch($id, $success);

        return ($success === TRUE && is_array($data))
            ? $data[0] : FALSE;
    }

    /**
     * Checks if cache exists and it hasn't expired
     *
     * @param  string $keyName
     * @return boolean
     */
    public function exists($keyName)
    {
        $success = FALSE;
        $data = apc_fetch($keyName, $success);

        return ($success === TRUE && is_array($data))
            ? true : FALSE;
    }

    /**
     * Cache Save
     * @param null $keyName
     * @param null $content
     * @param int $lifetime
     * @return array|bool
     */
    public function save($keyName = null, $content = null, $lifetime = 86400)
    {
        $ttl = (int)$lifetime;
        return apc_store($keyName, array(serialize($content), time(), $ttl), $ttl);
    }

    // ------------------------------------------------------------------------

    /**
     * Delete from Cache
     *
     * @param    mixed    unique identifier of the item in the cache
     * @return    bool    true on success/false on failure
     */
    public function delete($id)
    {
        return apc_delete($id);
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the cache
     *
     * @return    bool    false on failure/true on success
     */
    public function flush()
    {
        return apc_clear_cache('user');
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     *
     * @param    string    user/filehits
     * @return    mixed    array on success, false on failure
     */
    public function cacheInfo($type = NULL)
    {
        return apc_cache_info($type);
    }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata
     *
     * @param    mixed    key to get cache metadata on
     * @return    mixed    array on success/false on failure
     */
    public function getMetaData($id)
    {
        $success = FALSE;
        $stored = apc_fetch($id, $success);

        if ($success === FALSE OR count($stored) !== 3) {
            return FALSE;
        }

        list($data, $time, $ttl) = $stored;

        return array(
            'expire' => $time + $ttl,
            'mtime' => $time,
            'data' => unserialize($data)
        );
    }

    // ------------------------------------------------------------------------

    /**
     * is_supported()
     *
     * Check to see if APC is available on this system, bail if it isn't.
     *
     * @return    bool
     */
    public function isSupported()
    {
        if (!extension_loaded('apc') OR !(bool)@ini_get('apc.enabled')) {
// 			log_message('debug', 'The APC PHP extension must be loaded to use APC Cache.');
            return FALSE;
        }

        return TRUE;
    }

}