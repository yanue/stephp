<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 4/19/15
 * Time: 6:53 PM
 */

namespace Library\Di;


class Singleton
{
    /**
     * protected to prevent clonning
     **/
    protected function __clone()
    {
    }

    /**
     * protected so no one else can instance it
     **/
    protected function __construct()
    {
    }

    private static $_instances = array();

    final public static function getInstance()
    {
        $c = get_called_class();

        if (!isset(self::$_instances[$c])) {
            self::$_instances[$c] = new $c;
        }

        return self::$_instances[$c];
    }
}