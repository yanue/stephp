<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 4/19/15
 * Time: 6:53 PM
 */

namespace Library\Di;


trait Singleton
{
    private static $instance;

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}