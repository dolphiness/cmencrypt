<?php

return [
    "aes_keys" => [
        'aes_cipher'    => env('AES_CIPHER', 'aes-128-ecb'),
        'aes_key'       => env('AES_KEY', '1234567891234567'),
        'aes_iv'            => env('AES_IV', ''),
        'aes_tag'           => env('AES_TAG', ''),
        'aes_aad'           => env('AES_AAD', ''),
    ],

    //本系统rsa秘钥，公钥提供给其他系统
    "rsa_keys"  => [
        'public_key'    => storage_path("keys\\public_key.pem"),
        'private_key'   => storage_path("keys\\private_key.pem"),
        'password'      => env('RSA_PASSWORD', ''),
    ],

    //外部提供的rsa公钥
    "out_rsa_public_key"  => [
        'public_key'             => storage_path("keys\\public_key.pem"),
        'chapters_public_key'    => storage_path("keys\\chapters_public_key.pem"),
        'spotlight_public_key'   => storage_path("keys\\spotlight_public_key.pem"),
        'yproject_public_key'    => storage_path("keys\\yproject_public_key.pem"),
    ],

    'md5_keys'  => [
        'key'           => env('MD5_KEY', ''),
    ]
];