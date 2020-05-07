<?php
/**
 * AES加解密类.
 * User: mbl
 * Date: 2020-04-27
 * Time: 16:19
 */
namespace Stardust\crypt\library;

final class CryptAes
{
    const MY_NAME         = 'AES';
    protected static $cipher     = 'AES-128-ECB';
    protected static $secret_key = '';
    protected static $iv         = '';
    protected static $tag        = '';
    protected static $aad        = '';
    protected static $tagLen     = 16;
    protected static $algo       = 'sha256';
    protected static $options    = PKCS7_TEXT;

    public static function createCrypt(array $conf)
    {
        self::set_cipher($conf['cipher']);
        self::set_key($conf['key']);
        self::set_iv($conf['iv']);
        self::set_tag($conf['tag']);
        self::set_aad($conf['aad']);
    }

    public static function set_iv($iv)
    {

        self::$iv = substr($iv, 0, openssl_cipher_iv_length(self::$cipher));
    }

    public static function set_key($key)
    {
        self::$secret_key = substr($key, 0, 32);
    }
    public static function set_tag($tag)
    {
        self::$tag    = substr($tag, 0, 16);
        self::$tagLen = strlen(self::$tag);
    }
    public static function set_aad($aad)
    {
        self::$aad = substr($aad, 0, 16);
    }
    public static function set_algo($algo)
    {
        self::$algo = substr($algo, 0, 16);
    }

    public static function set_cipher($cipher)
    {
        if(!in_array($cipher, openssl_get_cipher_methods()))
        {
            die('The system does not support '.$cipher.' encryption mode.');
        }
        self::$cipher = $cipher;
    }

    /**
     * 加密
     * @param $value
     * @return string
     */
    public static function encrypt($value)
    {

        if(in_array(strtolower(substr(self::$cipher,-3)), ['gcm', 'ccm']))
        {
            $encrypt = openssl_encrypt($value, self::$cipher, self::$secret_key, self::$options, self::$iv, self::$tag,
                self::$aad, self::$tagLen);
        }else{
            $encrypt = openssl_encrypt($value, self::$cipher, self::$secret_key, self::$options, self::$iv);
        }
        return base64_encode($encrypt);
    }

    /**
     * 解密
     * @param $value
     * @return string
     */
    public static function decrypt($value)
    {
        $encrypt = base64_decode($value);
        if(in_array(strtolower(substr(self::$cipher,-3)), ['gcm', 'ccm'])) {
            $text = @openssl_decrypt($encrypt, self::$cipher, self::$secret_key, self::$options, self::$iv, self::$tag, self::$aad);
        }else{
            $text = @openssl_decrypt($encrypt, self::$cipher, self::$secret_key, self::$options, self::$iv);
        }

        return $text;
    }

    public static function getOpensslRandom($len)
    {
        return openssl_random_pseudo_bytes($len);
    }

    public static function getIvLen()
    {
        return openssl_cipher_iv_length(self::$cipher);
    }

    public static function createRandom($length)
    {
        $iv  = 'qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM';
        $len = strlen($iv)-1;
        $arr = str_split($iv);
        $txt = '';
        for($i=0;$i<$length;$i++)
        {
            $txt .= $arr[rand(0,$len)];
        }
        return $txt;
    }
}