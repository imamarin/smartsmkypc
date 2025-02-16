<?php

use Illuminate\Support\Facades\Config;

if (!function_exists('encryptSmart')) {
    function encryptSmart($data)
    {
        $key = Config::get('app.key'); // Menggunakan APP_KEY Laravel
        $method = 'AES-256-CBC';
        $iv = substr(hash('sha256', 'iv_static_key'), 0, 16); // IV statis

        return base64_encode(openssl_encrypt($data, $method, $key, 0, $iv));
    }
}

if (!function_exists('decryptSmart')) {
    function decryptSmart($encryptedData)
    {
        $key = Config::get('app.key');
        $method = 'AES-256-CBC';
        $iv = substr(hash('sha256', 'iv_static_key'), 0, 16); // IV statis

        return openssl_decrypt(base64_decode($encryptedData), $method, $key, 0, $iv);
    }
}
