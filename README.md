# Stardust Phplib Encrypt

## 安装
```
a.
composer.json添加
"require": {
    "stardust-phplib/cmencrypt": "1.2"
}

b.
composer require stardust-phplib/cmencrypt

```

## 初始化使用
秘钥请到config/key.php里配置
####laravel
1.添加服务，到 config/app.php的providers添加一行
```php
Stardust\crypt\CmencryptServiceProvider::class
```
2.添加Facades，到 config/app.php的aliases添加一行
```php
'Cmencrypt' => Stardust\crypt\Facades\Cmencrypt::class
```
####lumen
1.添加服务，到bootstrap/app.php下添加一行
```php
$app->register(Stardust\crypt\CmencryptServiceProvider::class)
```
2.添加Facades，到vendor\Illuminate\Support\Facades文件夹下添加Cmencrypt.php


## 使用示例

```php
use Illuminate\Support\Facades\Cmencrypt;

class Sign
{
    public function handle()
    {
        $data = ['b' => 1, 'a' => 2];
        $rsa_sign = Cmencrypt::RsaSign($data); //RSA签名
        $res = Cmencrypt::RsaVerifySign($data, $rsa_sign); //RSA验签
    
        $aes_crypt = Cmencrypt::AesEncrypt($data); //AES加密
        $res = Cmencrypt::AesDecrypt($aes_crypt); //AES解密
    }
}
```
