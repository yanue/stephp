<?php
namespace Library\Di;

class DI
{
    public static $registry = [];

    public static function bind($name, $val)
    {
        static::$registry[$name] = $val;
    }

    public static function get($name)
    {
        if (isset(static::$registry[$name])) {
            $resolver = static::$registry[$name];
            return $resolver;
        }
        return false;
    }
}