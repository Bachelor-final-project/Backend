<?php
function deterministicEncrypt($value)
{
    $key = config('app.key'); // Use a secure key
    return base64_encode(openssl_encrypt($value, 'AES-256-ECB', $key, OPENSSL_RAW_DATA));
}

function deterministicDecrypt($encryptedValue)
{
    $key = config('app.key'); // Use a secure key
    return openssl_decrypt(base64_decode($encryptedValue), 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
}


