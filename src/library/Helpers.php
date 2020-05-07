<?php
/**
 * 公共类库
 * User: mbl
 * Date: 2020-04-27
 * Time: 18:09
 */
namespace Stardust\crypt\library;


final class Helpers
{
    /**
     * 针对公钥和私钥进行修改
     * @param sting str    原字符串
     * @param string type  PRIVATE: 私钥，PUBLIC：公钥
     * @return string
     */
    public static function formatRSAKey($str, $type)
    {
        //格式化：公钥
        if ('PUBLIC' == $type) {
            $rsa = trim(str_replace(array('-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n", "\r"), '',
                $str));
            return "-----BEGIN PUBLIC KEY-----\n".chunk_split($rsa, 64, "\n")."-----END PUBLIC KEY-----";
        } else if ('PRIVATE' == $type) {
            $rsa = trim(str_replace(array('-----BEGIN RSA PRIVATE KEY-----', '-----END RSA PRIVATE KEY-----', "\n", "\r"), '',
                $str));
            return "-----BEGIN RSA PRIVATE KEY-----\n".chunk_split($rsa, 64, "\n")."-----END RSA PRIVATE KEY-----";
        }
    }

    /**
     * 对数组排序并拼接
     * @param array $data    原数组
     * @return string
     */
    public static function signFormat(array $data)
    {
        ksort($data);
        $query_string   = '';
        // $query_string   = http_build_query($data);
        foreach ($data as $key => $val)
        {
            $val = rawurlencode($val);
            $val = str_replace('%21', '!', $val);
            $val = str_replace('%2A', '*', $val);
            $val = str_replace('%27', "'", $val);
            $val = str_replace('%28', '(', $val);
            $val = str_replace('%29', ')', $val);
            $query_string .= $key.'='.$val.'&';
        }
        //去掉最后一个&符号
        $query_string = substr($query_string,0,strlen($query_string) - 1);
        return $query_string;
    }
}