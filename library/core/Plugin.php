<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 10/25/14
 * Time: 9:31 AM
 */

namespace Library\Core;


use Library\Di\DI;
use Library\Di\Injectable;

class Plugin extends Injectable
{
    public function __construct()
    {
        $this->setDI(new DI());
    }
} 