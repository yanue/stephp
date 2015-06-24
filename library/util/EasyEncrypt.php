<?php

/**
 * 简单对称加密算法之加密
 * @author yanue <yanue@outlook.com>
 * @date 2015-05-10
 * @return String
 */

namespace Library\Util;

class EasyEncrypt
{
    const SECRET_KEY = 'estt';

    /**
     * 简单加密
     * @param String $string 需要加密的字串
     * @return String
     */
    static function encode($string = '')
    {
        $skey = str_split(base64_encode(self::SECRET_KEY));
        $strArr = str_split(base64_encode($string));
        $strCount = count($strArr);
        foreach ($skey as $key => $value) {
            $key < $strCount && $strArr[$key] .= $value;
        }
        return str_replace('=', '-', join('', $strArr));
    }

    /**
     * 简单解密
     * @param String $string 需要解密的字串
     * @return String
     */
    static function decode($string = '')
    {
        if (is_numeric($string)) {
            return $string;
        }
        $skey = str_split(base64_encode(self::SECRET_KEY));
        $strArr = str_split(str_replace('-', '=', $string), 2);
        $strCount = count($strArr);
        foreach ($skey as $key => $value) {
            $key < $strCount && @$strArr[$key][1] === $value && $strArr[$key] = @$strArr[$key][0];
        }
        return base64_decode(join('', $strArr));
    }

}