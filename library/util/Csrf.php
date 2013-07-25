<?php
namespace Library\Util\Csrf;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');


/**
 * CSRF 处理
 *
 *
 * A cross-site request forgery prevention class for Kohana 3. Includes user-agent
 * string validation, private key (secret) validation, and an expiration time.
 * The expiration time can be passed as 0, FALSE, or NULL to indicate no expiration.
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class CSRF {

    /**
     * Static vars to ensure we don't re-run token generation. Don't change.
     */
    private static $alreadyIncludedJavascript = FALSE;
    private static $alreadySetToken = FALSE;


    /**
     * key to hash off of. Change this your csrf config file
     *
     * @return sting
     */
    public  static function _secret_key()
    {
        return "csrf.secret";
    }

    /**
     * Returns the token in the session or generates a new one if none previously
     * existed. If you pass in TRUE for the $new variable, you can force a
     * token to be newly created if it's the first case of CSRF::token() being
     * called. This is useful to force recreation of tokens after a POST request
     * or following an AJAX request.
     *
     * @access	public static
     * @param	bool	$new			Whether to force creation
     * @return 	string
     */
    public static function token($new = FALSE)
    {
        $token = Session::instance()->get('csrf-token');
        if (!$token || (!self::$alreadySetToken && $new)) {
            // get the user agent
            $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'No User Agent';
            // create encrypted token based on secret, user agent, unique identifier, and action
            $token = self::encrypt(self::_secret_key() . $user_agent . uniqid(rand(), TRUE));
            //$token = sha1(self::_secret_key() . $user_agent . uniqid(rand(), TRUE)) . '|' . (time() + $expiration);
            // set in the session
            Session::instance()->set('csrf-token', $token);
        }
        self::$alreadySetToken = TRUE;
        return $token;
    }

    /**
     * Validation rule for checking a valid token. Default expiration is
     * set for 10 minutes.
     *
     * @access	public static
     * @param	string	$token
     * @param	int		$expiration
     * @return 	bool
     */
    public static function valid($token, $expiration = 600)
    {
        // grab current token if set (otherwise generate)
        $current_token = self::token();
        $current_token = self::encrypt($current_token, NULL, FALSE);
        // decrypt the POSTed token
        $token = self::encrypt($token, NULL, FALSE);
        // split the token to get the date
        list($current_token, $time) = explode('||', $current_token);
        // delete the old token
        Session::instance()->delete('csrf-token');
        // return if the tokens match and haven't expired
        if ($time) return $token === $current_token && (($time + $expiration) >= time());
        return $token === $current_token && ($time == 0);
    }

    /**
     * Encrypts the token.
     *
     * @access	public static
     * @param	$token
     * @param	$expiration		The expiration time
     * @param	$encrypt		Whether to encrypt or decrypt
     */
    public static function encrypt($token, $encrypt = TRUE)
    {
        if (!function_exists('mcrypt_encrypt')) {
            if ($encrypt) {
                return sha1($token) . '||' . time();
            }

            return $token;
        }
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        if ($encrypt) {
            $token .= '||' . time();
            return bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::_secret_key(), $token, MCRYPT_MODE_ECB, $iv));
        }

        $len = strlen($token);
        $token = pack("H" . $len, $token);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::_secret_key(), $token, MCRYPT_MODE_ECB, $iv));
    }

    /**
     * Returns jquery code to get token
     *
     * @return bool
     */
    public static function javascript()
    {
        if (!self::$alreadyIncludedJavascript) {
            // make sure we've got a token set
            $current_token = self::token();

            $javascript  = '<script type="text/javascript">';
            $javascript .= 'var csrf_token = "' . $current_token . '";';
            $javascript .= 'var csrf_invalidated = false;';
            $javascript .= 'function getToken(callback) { $.getJSON("'.url::site('csrf/generate/').'", function(json) { csrf_token = json.token; csrf_invalidated = false; $(form).each(function(){ $(this).find("#csrf_token").val(csrf_token); }); if ($.isFunction(callback)) callback.call(this, csrf_token); }); }';
            $javascript .= '</script>';

            // set included
            self::$alreadyIncludedJavascript = true;

            // return javascript
            return $javascript;
        }
    }

}