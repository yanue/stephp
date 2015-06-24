<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 6/12/15
 * Time: 12:50 PM
 */

namespace library\Util;


use Library\Core\Plugin;

class Asset extends Plugin
{
    private static $js = [];
    private static $css = [];

    public function addCss($css)
    {
        array_push(self::$css, $css);
        self::$css = array_unique(self::$css);
        $this->view->css = self::$css;
    }

    public function addJs($js)
    {
        array_push(self::$js, $js);
        self::$js = array_unique(self::$js);
        $this->view->js = self::$js;
    }
}