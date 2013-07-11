<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/**
 * Hash 加密处理
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Hash
{
    
    /**
     * 创建hash算法
     *
     * @param string $algo The algorithm (md5, sha1, whirlpool, etc)
     * @param string $data The data to encode
     * @param string $salt The salt (This should be the same throughout the system probably)
     * @return string The hashed/salted data
     */
    public static function create($algo, $data, $salt='!@:\"#$%^&*<>?{}$^$@*^&*I@!')
    {
        
        $context = hash_init($algo, HASH_HMAC, $salt);
        hash_update($context, $data);
        
        return hash_final($context);
        
    }

}