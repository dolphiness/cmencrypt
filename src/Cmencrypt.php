<?php
namespace Stardust\crypt;

use Stardust\crypt\library\CryptRsa;
use Stardust\crypt\library\CryptAes;
use Stardust\crypt\library\Helpers;

class Cmencrypt
{
    /**
     * crypt constructor.
     */
    public function __construct()
    {
    }
    /** RSA签名
     * @param array $data 待签名数组
     * @return string
     */
    public function RsaSign($data){
        $data = Helpers::signFormat($data);
        $private_key_path = config('key.rsa_keys.private_key');
        CryptRsa::setPrivateKey(file_get_contents($private_key_path));
        $sign = CryptRsa::sign($data);
        return $sign;
    }

    /** RSA验签
     * @param array $data 待验签数组
     * @param string $sign 签名串
     * @param string $source 请求来源（用于获取不同系统的公钥）
     * @return string
     */
    public function RsaVerifySign($data,$sign,$source = ''){
        $data = Helpers::signFormat($data);
        $source = $source ? $source . '_' : '';
        $public_key_path = config('key.out_rsa_public_key.' . $source .'public_key');
        CryptRsa::setPublicKey(file_get_contents($public_key_path));
        return CryptRsa::signVerify($data, $sign);
    }

    /** AES加密
     * @param array $data 待加密数组
     * @return string
     */
    public function AesEncrypt($data){
        $aesConfig = config('key.aes_keys');
        CryptAES::createCrypt([
            'cipher' => $aesConfig['aes_cipher'] ? $aesConfig['aes_cipher'] : 'aes-128-ecb',
            'key'    => $aesConfig['aes_key'],
            'iv'     => $aesConfig['aes_iv'],
            'tag'    => $aesConfig['aes_tag'],
            'aad'    => $aesConfig['aes_aad'],
        ]);
        $data = CryptAES::encrypt(json_encode($data,JSON_UNESCAPED_UNICODE));
        return $data;
    }

    /** AES解密
     * @param array $data 待解密串
     * @return string
     */
    public function AesDecrypt($data){
        $aesConfig = config('key.aes_keys');
        CryptAES::createCrypt([
            'cipher' => $aesConfig['aes_cipher'] ? $aesConfig['aes_cipher'] : 'aes-128-ecb',
            'key'    => $aesConfig['aes_key'],
            'iv'     => $aesConfig['aes_iv'],
            'tag'    => $aesConfig['aes_tag'],
            'aad'    => $aesConfig['aes_aad'],
        ]);
        $data = CryptAES::decrypt($data);
        return $data;
    }
}