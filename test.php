<?php
/**
 * test.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @date        2013-07-26
 */
switch ($_SERVER['HTTP_ACCEPT']){
    case 'application/json, text/javascript, */*':
        //  JSON 格式
        break;
    case 'text/javascript, application/javascript, */*':
        // javascript 或 JSONP 格式
        break;
    case 'text/html, */*':
        //  HTML 格式
        break;
    case 'application/xml, text/xml, */*':
        //  XML 格式
        break;
}