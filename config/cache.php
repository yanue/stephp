<?php
return array(
    'cache.defaultDriver' => "redis",
    'cache.backUp' => 'apc',
    'cache.keyPrefix' => 'cache_',
    'cache.drivers.memcached' => array(
        array(
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 1
        ),
        /* array(
            'host' => '192.168.1.168',
            'port' => 11211,
            'weight' => 1
        ), */
    ),
    //队列
    'cache.drivers.memcacheq' => array(
        array(
            'host' => '192.168.1.170',
            'port' => 11211,
            'weight' => 1
        ),
        array(
            'host' => '192.168.1.168',
            'port' => 11211,
            'weight' => 1
        ),
    ),
    /*
    'cache.drivers.redis' => array(
        'host' => '',
        'port' => '',
        'timeout' => 1111, //秒
        'password => ''
    ), //把redis当缓存服务器使用
    */
);
