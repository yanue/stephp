<?php
namespace Library\Util;

use Library\Core\Config;
use Library\Core\Exception;

/**
 * Crypt PHP
 *
 * Provides cryptography functionality, including hashing and symmetric-key encryption
 *
 * @package    Crypt
 * @author       Osman Üngür <osmanungur@gmail.com>
 * @copyright  2010-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/crypt-php
 */
class Crypt
{
    private $data;
    private $key;
    private $complexTypes = false;
    const HMAC_ALGORITHM = 'sha1';
    const DELIMITER = '#';
    const MCRYPT_MODULE = 'rijndael-192';
    const MCRYPT_MOD = 'cfb';
    const PREFIX = 'Crypt';
    const MINIMUM_KEY_LENGTH = 8;
    const MCRYPT_CIPHER = MCRYPT_RIJNDAEL_128;

    private static $instance = null;
    /**
     * @var
     */
    private static $module = null;

    function __construct()
    {
        $this->checkEnvironment();
        $key = Config::getSite('crypt', 'encrypt.secret');
        $this->key = $key;
        $this->mode = self::MCRYPT_MOD;
        $this->cipher = self::MCRYPT_CIPHER;
        if (empty(self::$module)) {
            $iv_size = mcrypt_get_iv_size(self::MCRYPT_CIPHER, self::MCRYPT_MOD);
            self::$module = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        }
    }

    public static function instance()
    {
        if (!self::$instance instanceof static) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws Exception
     */
    private function checkEnvironment()
    {
        if ((!extension_loaded('mcrypt')) || (!function_exists('mcrypt_module_open'))) {
            throw new Exception('缺少php-mcrypt扩展', 1);
        }

        if (!in_array(self::MCRYPT_MODULE, mcrypt_list_algorithms())) {
            throw new Exception("加密方式不支持", 1);
        }
    }

    /**
     * Encrypts the given data using symmetric-key encryption
     *
     * @return string
     * @author Osman Üngür
     */
    public function encrypt($data)
    {
        mt_srand();
        $init_vector = mcrypt_create_iv(mcrypt_enc_get_iv_size(self::$module), MCRYPT_RAND);
        $key = substr(sha1($this->key), 0, mcrypt_enc_get_key_size(self::$module));
        mcrypt_generic_init(self::$module, $key, $init_vector);

        $cipher = mcrypt_generic(self::$module, $data);
        $hmac = hash_hmac(self::HMAC_ALGORITHM, $init_vector . self::DELIMITER . $cipher, $this->key);
        $encoded_init_vector = base64_encode($init_vector);
        $encoded_cipher = base64_encode($cipher);
        return self::PREFIX . self::DELIMITER . $encoded_init_vector . self::DELIMITER . $encoded_cipher . self::DELIMITER . $hmac;
    }

    public function decrypt($data)
    {
        $elements = explode(self::DELIMITER, $data);
        if (count($elements) != 4 || $elements[0] != self::PREFIX) {
            $message = sprintf('The given data does not appear to be encrypted with %s', __CLASS__);
            throw new Exception($message, 1);
        }
        $init_vector = base64_decode($elements[1]);
        $cipher = base64_decode($elements[2]);
        $given_hmac = $elements[3];
        $hmac = hash_hmac(self::HMAC_ALGORITHM, $init_vector . self::DELIMITER . $cipher, $this->key);
        if ($given_hmac != $hmac) {
            throw new Exception('The given data appears tampered or corrupted', 1);
        }
        $key = substr(sha1($this->key), 0, mcrypt_enc_get_key_size(self::$module));
        mcrypt_generic_init(self::$module, $key, $init_vector);
        $result = mdecrypt_generic(self::$module, $cipher);
        return $result;
    }

    public function __destruct()
    {
        @mcrypt_generic_deinit(self::$module);
        mcrypt_module_close(self::$module);
    }

}

?>