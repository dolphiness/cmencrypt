<?php
/**
 * Auth Code加解密类.
 * User: mbl
 * Date: 2020-04-27
 * Time: 16:19
 */
namespace Stardust\crypt\library;

final class CryptAuthCode
{
    const MY_NAME                = 'AuthCode';
    protected static $secret_key = '';
    protected static $expiry     = '';
    protected static $random_key = '63a917c058b7df126d7de87557e6a693';

    public static function createCrypt($key, $expiry = 0)
    {
        self::set_key($key);
        self::set_expiry($expiry);
    }

    public static function set_expiry($expiry)
    {
        self::$expiry = $expiry;
    }

    public static function set_key($key)
    {
        self::$secret_key = substr($key, 0, 32);
    }

    public static function encrypt($string)
    {
        return self::authcode($string, 'ENCODE', self::$secret_key, self::$expiry);
    }

    public static function decrypt($string)
    {
        return self::authcode($string, 'DECODE', self::$secret_key);
    }

    private static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;
        $key  = md5(($key ? $key : self::$secret_key). self::$random_key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey   = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = [];
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }

}