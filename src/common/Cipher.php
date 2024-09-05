<?php
namespace Benjaminzwahlen\Brace\common;
class Cipher
{
    public static function encrypt(#[\SensitiveParameter] $data, #[\SensitiveParameter] $key): string
    {
        $plaintext = $data;
        $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
        return $ciphertext;
    }

    public static function decrypt(#[\SensitiveParameter] $data,  #[\SensitiveParameter] $key): string
    {
        $c = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        }
    }

    public static function hash(#[\SensitiveParameter] string $str): string
    {
        return sha1($str);
    }
}
