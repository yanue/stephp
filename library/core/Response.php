<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 6/3/15
 * Time: 6:01 PM
 */

namespace Library\Core;

class Response
{
    public static function redirect($url, $time = 0)
    {
        if (is_numeric($time) && $time > 0) {
            header('refresh:' . $time . ';url=' . $url);//限时跳转
        } else {
            header('location:' . $url);//立即跳转
        }
    }
}