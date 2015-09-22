<?php
namespace Library\Util;

/**
 * Validator
 *
 * @copyright    http://yanue.net/
 * @author        yanue <yanue@outlook.com>
 * @version        1.0.0 - 2013-07-19
 */
use DateTime;


class Validator
{
    public function __construct()
    {

    }

    /**
     * 验证用户名
     * --说明:长度为5-16位字符长度,只能包含字母数字(开始必须字母).
     * @param $username
     * @return array|int
     */
    public static function validUsername($username)
    {
        if (strlen($username) > 16 || strlen($username) < 5) {
            return false;
        }
        if (preg_match('/^([a-z]+)([a-z0-9]+)/i', $username) == 0) {
            return false;
        };
        return true;
    }

    /**
     * 验证用户姓名
     * --说明:长度为2-10位中文.
     * @param $username
     * @return array|int
     */
    public static function validRealName($username)
    {

        if (!preg_match('/^([\x{4e00}-\x{9fa5}]{2,10})$/', $username)) {
            return false;
        };

        return true;
    }

    /**
     * 验证密码
     * --说明:长度为6-16位字符长度
     * @param $passwd
     * @return array|int
     */
    public static function validPassword($passwd)
    {
        return (strlen($passwd) <= 16 && strlen($passwd) >= 6);
    }

    /**
     * Validate that an attribute is numeric.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Validate an attribute is contained within a list of values.
     *
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateIn($value, $parameters)
    {
        return in_array($value, $parameters);
    }

    /**
     * Validate an attribute is not contained within a list of values.
     *
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateNotIn($value, $parameters)
    {
        return !in_array($value, $parameters);
    }


    /**
     * Validate that an attribute is a valid IP.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateIp($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate that an attribute is a valid e-mail address.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate that an attribute is a valid URL.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateUrl($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate that an attribute is a valid URL.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateImgUrl($value)
    {
        $isUrl = filter_var($value, FILTER_VALIDATE_URL) !== false;
        $res = false;
        if ($isUrl) {
            $res = preg_match('/.*(?:jpg|gif|png)$/i', $value);
        }
        return $res;
    }

    /**
     * 验证集装箱号
     * @param $strCode
     * @return bool
     */
    public static function validateContainerNo($strCode)
    {
        $Charcode = "0123456789A?BCDEFGHIJK?LMNOPQRSTU?VWXYZ";
        if (strlen($strCode) != 11)
            return false;

        $num = 0;
        for ($i = 0; $i < 10; $i++) {
            $idx = strpos($Charcode, $strCode[$i]);
            if ($idx === -1 || $Charcode[$idx] === '?') {
                break;
            }
            $idx = $idx * pow(2, $i);
            $num += $idx;
        }

        $num = ($num % 11) % 10;
        return intval($strCode[10]) == $num;
    }

    /**
     * 验证订单编号
     *
     * @param $order
     * @return bool|int
     */
    public static function validateContainerOrder($order)
    {
        if (strlen($order) != 10) return false;
        # IO|IC|EO|EC+8位数字
        $res = preg_match('/^(IO|IC|EO|EC|RO)([0-9]{8})$/i', $order, $matches);
        return $res;
    }

    /**
     * Validate that an attribute is an active URL.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateActiveUrl($value)
    {
        $url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));

        return checkdnsrr($url);
    }

    /**
     * Validate the MIME type of a file is an image MIME type.
     *
     * @param  mixed $value
     * @return bool
     */
    public function validateImage($value)
    {
        return $this->validateMimes($value, array('jpeg', 'png', 'gif', 'bmp'));
    }

    /**
     * Validate the MIME type of a file upload attribute is in a set of MIME types.
     *
     * @param  array $value
     * @param  array $parameters
     * @return bool
     */
    public function validateMimes($value, $parameters)
    {
        if (!$value instanceof File or $value->getPath() == '') {
            return true;
        }

        // The Symfony File fdfs should do a decent job of guessing the extension
        // based on the true MIME type so we'll just loop through the array of
        // extensions and compare it to the guessed extension of the files.
        return in_array($value->guessExtension(), $parameters);
    }

    /**
     * Validate that an attribute contains only alphabetic characters.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlpha($value)
    {
        return preg_match('/^([a-z])+$/$i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlphaNum($value)
    {
        return preg_match('/^([a-z0-9])+$/$i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlphaDash($value)
    {
        return preg_match('/^([a-z0-9_-])+$/$i', $value);
    }

    /**
     * Validate that an attribute passes a regular expression check.
     *
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateRegex($value, $parameters)
    {
        return preg_match($parameters[0], $value);
    }

    /**
     * Validate that an attribute is a valid date.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateDate($value)
    {
        if ($value instanceof DateTime) return true;

        if (strtotime($value) === false) return false;

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * Validate that an attribute matches a date format.
     *
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public static function validateDateFormat($value, $parameters)
    {
        $parsed = date_parse_from_format($parameters[0], $value);

        return $parsed['error_count'] === 0;
    }

    /**
     * Validate the date is before a given date.
     *
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    public function validateBefore($value, $parameters)
    {
        if (!($date = strtotime($parameters[0]))) {
            return strtotime($value) < strtotime($this->getValue($parameters[0]));
        } else {
            return strtotime($value) < $date;
        }
    }

    public static function validateAliasName($name)
    {
        if (strlen($name) > 20 || strlen($name) < 1) {
            return false;
        }
        if (preg_match('/^([a-z]+)([0-9a-zA-Z_-]+)$/$i', $name) == 0) {
            return false;
        };
        return true;
    }

    public static function validateCellPhone($phone)
    {
        return self::validateRegex($phone, '/^1[\d]{10}$/');
    }

    /**
     * 验证身份证号
     *
     * @param $idcard
     * @return bool
     */
    public static function validateIDCard($idcard)
    {
        // 只能是18位
        if (strlen($idcard) != 18) {
            return false;
        }

        // 取出本体码
        $idcard_base = substr($idcard, 0, 17);

        // 取出校验码
        $verify_code = substr($idcard, 17, 1);

        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

        // 根据前17位计算校验码
        $total = 0;
        for ($i = 0; $i < 17; $i++) {
            $total += substr($idcard_base, $i, 1) * $factor[$i];
        }

        // 取模
        $mod = $total % 11;

        // 比较校验码
        if ($verify_code == $verify_code_list[$mod]) {
            return true;
        } else {
            return false;
        }
    }
}