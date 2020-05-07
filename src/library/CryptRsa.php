<?php
/**
 * RSA.
 * User: mbl
 * Date: 2020-04-27
 * Time: 18:09
 */
namespace Stardust\crypt\library;


final class CryptRsa
{
    const MY_NAME = 'RSA';
    private static $_private_key = '';
    private static $_public_key = '';
    private static $_private_key_password = '';
    private static $_rsa_type = OPENSSL_ALGO_SHA1;

    /**
     * @param $type OPENSSL_ALGO_SHA1、OPENSSL_ALGO_SHA256
     * 注意：OPENSSL_ALGO_SHA1秘钥可以是1024和2048
     *      OPENSSL_ALGO_SHA256的秘钥生成必须为2048位
     *      秘钥格式：PKCS#8
     */
    public static function setRsaType($type)
    {
        self::$_rsa_type = $type;
    }

    public static function setPrivateKey($private_key)
    {
        self::$_private_key = $private_key;
    }

    public static function setPublicKey($public_key)
    {
        self::$_public_key = $public_key;
    }

    public static function setPrivKeyPass($password)
    {
        self::$_private_key_password = $password;
    }

    /**
     * 私钥加密
     * @param $data
     * @return string
     */
    public static function privateEncrypt($data)
    {
        $encrypted = '';
        //这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        if (self::$_private_key_password) {
            $private_key = openssl_pkey_get_private(self::$_private_key, self::$_private_key_password);
        } else {
            $private_key = openssl_pkey_get_private(self::$_private_key);
        }

        //this data length > 117 bits error coder for yee split 117.
        $plainData = str_split($data, 117);//priavte private key bits 1024.
        foreach ($plainData as $chunk) {
            $str = '';
            $encryption = openssl_private_encrypt($chunk, $str, $private_key, OPENSSL_PKCS1_PADDING);
            if ($encryption === false) {
                return false;
            }
            $encrypted .= $str;
        }
        //encrypted coder base64_encode.
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }

    /**
     * 私钥解密
     * @param $encrypted
     * @return mixed
     */
    public static function privateDecrypt($encrypted)
    {
        $decrypted = '';
        if (self::$_private_key_password) {
            $private_key = openssl_pkey_get_private(self::$_private_key, self::$_private_key_password);
        } else {
            $private_key = openssl_pkey_get_private(self::$_private_key);
        }
        $plainData = str_split(base64_decode($encrypted), 128);
        foreach ($plainData as $chunk) {
            $str = '';
            $decryption = openssl_private_decrypt($chunk, $str, $private_key, OPENSSL_PKCS1_PADDING);
            if ($decryption === false) {
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }

    /**
     * 公钥加密
     * @param $data
     * @return string
     */
    public static function publicEncrypt($data)
    {
        $encrypted  = '';
        $public_key = openssl_pkey_get_public(self::$_public_key);
        $plainData  = str_split($data, 117);
        foreach ($plainData as $chunk) {
            $str = '';
            $encryption = openssl_public_encrypt($chunk, $str, $public_key, OPENSSL_PKCS1_PADDING);
            if ($encryption === false) {
                return false;
            }
            $encrypted .= $str;
        }
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }

    /**
     * 公钥解密
     * @param $encrypted
     * @return mixed
     */
    public static function publicDecrypt($encrypted)
    {
        $decrypted  = '';
        $public_key = openssl_pkey_get_public(self::$_public_key);
        $plainData  = str_split(base64_decode($encrypted), 128);
        foreach ($plainData as $chunk) {
            $str = '';
            $decryption = openssl_public_decrypt($chunk, $str, $public_key, OPENSSL_PKCS1_PADDING);
            if ($decryption === false) {
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }


    /**
     * 私钥签名
     * @param $data
     * @param $privateKey
     * @return string
     */
    public static function sign($data, $privateKey = '')
    {
        $privateKey = $privateKey ? $privateKey : self::$_private_key;
        if (self::$_private_key_password) {
            $private_key = openssl_pkey_get_private($privateKey, self::$_private_key_password);
        } else {
            $private_key = openssl_pkey_get_private($privateKey);
        }
        openssl_sign($data, $signData, $private_key, self::$_rsa_type);
        $signData = base64_encode($signData);
        return $signData;
    }

    /**
     * 公钥验证签名
     * @param $data
     * @param $sign
     * @param $publicKey
     * @return bool
     */
    public static function signVerify($data, $sign, $publicKey = '')
    {
        $publicKey = $publicKey ? $publicKey : self::$_public_key;
        $signCode = base64_decode($sign);
        //coder check verify, correct return 1 error returns 0
        if (openssl_verify($data, $signCode, $publicKey, self::$_rsa_type)) {
            return true;
        }
        return false;
    }

}