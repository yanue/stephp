<?php
namespace Library\Util;

/**
 * errorCode.php
 *
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.0 - 2013-07-19
 */

/**
 * Class Validator
 *
 */
class Validator{

    /**
     * Create a new Validator instance.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @return void
     */
    public function __construct(){

    }

    /**
     * 验证用户名
     * --说明:长度为5-16位字符长度,只能包含字母数字(开始必须字母).
     * @param $username
     * @return array|int
     */
    public function validUsername($username){
        if(strlen($username) > 16 || strlen($username) < 5){
            return ERROR_USER_IS_INVALID;
        }
        if(preg_match('/^([a-z]+)([a-z0-9]+)$/i', $username)==0){
            return ERROR_USER_IS_INVALID;
        };
        return true;
    }

    /**
     * 验证用户名
     * --说明:长度为5-16位字符长度,只能包含字母数字(开始必须字母).
     * @param $username
     * @return array|int
     */
    public function validPassword($passwd){
        if(strlen($passwd) > 16 || strlen($passwd) < 6){
            return ERROR_PASSWD_IS_INVALID;
        }
        return true;
    }

    /**
     * Validate that an attribute is numeric.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * Validate an attribute is contained within a list of values.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateIn($value, $parameters)
    {
        return in_array($value, $parameters);
    }

    /**
     * Validate an attribute is not contained within a list of values.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateNotIn($value, $parameters)
    {
        return ! in_array($value, $parameters);
    }




    /**
     * Validate that an attribute is a valid IP.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateIp($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate that an attribute is a valid e-mail address.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate that an attribute is a valid URL.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateUrl($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate that an attribute is an active URL.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateActiveUrl($value)
    {
        $url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));

        return checkdnsrr($url);
    }

    /**
     * Validate the MIME type of a file is an image MIME type.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateImage($value)
    {
        return $this->validateMimes($value, array('jpeg', 'png', 'gif', 'bmp'));
    }

    /**
     * Validate the MIME type of a file upload attribute is in a set of MIME types.
     *
     * @param  string  $attribute
     * @param  array   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateMimes($value, $parameters)
    {
        if ( ! $value instanceof File or $value->getPath() == '')
        {
            return true;
        }

        // The Symfony File class should do a decent job of guessing the extension
        // based on the true MIME type so we'll just loop through the array of
        // extensions and compare it to the guessed extension of the files.
        return in_array($value->guessExtension(), $parameters);
    }

    /**
     * Validate that an attribute contains only alphabetic characters.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateAlpha($value)
    {
        return preg_match('/^([a-z])+$/i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateAlphaNum($value)
    {
        return preg_match('/^([a-z0-9])+$/i', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateAlphaDash($value)
    {
        return preg_match('/^([a-z0-9_-])+$/i', $value);
    }

    /**
     * Validate that an attribute passes a regular expression check.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateRegex($value, $parameters)
    {
        return preg_match($parameters[0], $value);
    }

    /**
     * Validate that an attribute is a valid date.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateDate($value)
    {
        if ($value instanceof DateTime) return true;

        if (strtotime($value) === false) return false;

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * Validate that an attribute matches a date format.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateDateFormat($value, $parameters)
    {
        $parsed = date_parse_from_format($parameters[0], $value);

        return $parsed['error_count'] === 0;
    }

    /**
     * Validate the date is before a given date.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateBefore($value, $parameters)
    {
        if ( ! ($date = strtotime($parameters[0])))
        {
            return strtotime($value) < strtotime($this->getValue($parameters[0]));
        }
        else
        {
            return strtotime($value) < $date;
        }
    }
}