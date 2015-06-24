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
        if (preg_match('/^([a-z]+)([a-z0-9]+)$/i', $username) == 0) {
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
        return preg_match('/^([a-z])+$/i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlphaNum($value)
    {
        return preg_match('/^([a-z0-9])+$/i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function validateAlphaDash($value)
    {
        return preg_match('/^([a-z0-9_-])+$/i', $value);
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
        if (preg_match('/^([a-z]+)([0-9a-zA-Z_-]+)$/i', $name) == 0) {
            return false;
        };
        return true;
    }
}