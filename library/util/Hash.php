<?php
namespace Library\Util;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * Hash 加密处理
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Hash
{
    const ALGO = 'sha1';
    const SALT = '!@:\"#$%^&*<>?{}$^$@*^&*I@!';

    /**
     * 创建hash算法
     *
     * @param string $data The data to encode
     * @return string The hashed/salted data
     */
    public static function create($data)
    {
        $context = hash_init(self::ALGO, HASH_HMAC, self::SALT);
        hash_update($context, $data);

        return hash_final($context);
    }

}