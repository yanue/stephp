<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-16
 * Time: 下午1:05
 */

// Always provide a TRAILING SLASH (/) AFTER A PATH
define('URL', 'http://workspace/mvc/');
define('LIBS', 'libs/');

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'looklo');
define('DB_USER', 'root');
define('DB_PASS', '');


// define for images server
define('IMG_HOST', '192.168.0.154');
define('IMG_PORT', '80');
define('IMG_PATH','/img/');
define('IMAGE_SERVER', 'http://'.IMG_HOST.IMG_PATH);




// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet
define('HASH_GENERAL_KEY', 'lojasdiirfhiqwerndf');

// This is for database passwords only
define('HASH_PASSWORD_KEY', 'eS$Wdse$^*_!&)+cf}"ghe|~f<');