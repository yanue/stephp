<?php

/**
 * User: yanue
 * Date: 10/22/13
 * Time: 2:02 PM
 * Modified: wgwang 2013-10-25
 */
namespace Library\Core;

class Config
{
    static $settings = null;

    /**
     * 获取任意配置，如果是非config文件的配置，需要先load
     *
     * @param $key
     * @param null $file
     * @return null|string
     */
    public static function getItem($key, $file = null)
    {
        if (empty ($key)) {
            return null;
        }
        if ($file) {
            return self::getSite($file, $key);
        }

        $val = self::getBase($key);
        if (!is_null($val)) {
            return $val;
        } else {
            foreach (self::$settings as $conf) {
                if (isset ($conf [$key])) {
                    return $conf [$key];
                }
            }
            return null;
        }
    }

    /**
     * 获取基本配置信息
     *
     * @param
     *            $key
     * @return string
     */
    public static function getBase($key)
    {
        if (!isset (self::$settings ['config'])) {
            self::load();
        }
        return isset (self::$settings ['config'] [$key]) ? self::$settings ['config'] [$key] : null;
    }

    /**
     * load config data into static scale
     *
     * @param string $file
     */
    public static function load($file = 'config')
    {
        if (!isset (self::$settings [$file])) {
            $appSettings = array();
            $configFile = WEB_ROOT . '/config/' . $file . '.php';
            if (file_exists($configFile)) {
                $appSettings = include_once($configFile);
            }
            unset ($configFile);
            self::$settings [$file] = $appSettings;
            unset ($appSettings);
        }
        return self::$settings [$file];
    }

    /**
     * 修改一个配置，如果是在非config文件中设置的，需要先load
     *
     * @param $key
     * @param $val
     * @param null $file
     * @return bool
     */
    public static function setItem($key, $val, $file = null)
    {
        if (empty ($key) || is_null($val)) {
            return false;
        }
        if (isset (self::$settings [$key])) {
            self::$settings [$key] = $val;
            return true;
        }
        foreach (self::$settings as $kk => $conf) {
            if (isset ($conf [$key])) {
                self::$settings [$kk] [$key] = $val;
                return true;
            }
        }
        return false;
    }

    /**
     * 获取site->config配置文件信息
     *
     * @param
     *            $file
     * @param
     *            $key
     * @return null string
     */
    public static function getSite($file, $key = null)
    {
        $full_file = WEB_ROOT . '/config/' . $file . '.php';
        if (!isset(self::$settings ['config'] [$file])) {
            self::$settings ['config'] [$file] = include($full_file);
        }
        if (!$key) {
            return self::$settings ['config'] [$file];
        }
        return isset (self::$settings ['config'] [$file] [$key]) ? self::$settings ['config'] [$file] [$key] : null;
    }

    /**
     * 获取app->router路由信息
     *
     * @return string
     */
    public static function getRouter()
    {

        $full_file = WEB_ROOT . '/config/router.php';

        if (!isset(self::$settings['router'])) {
            self::$settings['router'] = file_exists($full_file) ? include($full_file) : null;
        } else {

        }

        return self::$settings['router'];
    }

}