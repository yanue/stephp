<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 6/2/15
 * Time: 3:07 PM
 */

namespace service;


use Library\Core\Plugin;
use Model\UserModel;

class UserManager extends Plugin
{
    private static $instance = null;

    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get()
    {
        print_r($this->uri->getFullUrl());
        print_r($this->db->from('user')->fetch());
        UserModel::findFirst();

    }
}