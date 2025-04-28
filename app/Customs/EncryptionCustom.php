<?php

namespace App\Customs;

use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionCustom 
{
    /**
     * The algorithm used for encryption.
     *
     * @var string
     * aes-128-cbc : use 16 alphanum custom key
     */
    private static $chipper = 'aes-128-cbc';

    /**
     * Encrypt a given string using a custom secret key without serialize.
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    public static function encrypt($data, $key=null)
    {
        return (new \Illuminate\Encryption\Encrypter($key ?: config('api-config.custom_key'), self::$chipper))
                ->encrypt($data, false);
    }

    /**
     * Decrypt an encrypted string using a custom secret key without serialize.
     *
     * @param string $encryptedData
     * @param string $key
     * @return string|null
     */
    public static function decrypt($encryptedData, $key=null)
    {
        try {
            return (new \Illuminate\Encryption\Encrypter($key ?: config('api-config.custom_key'), self::$chipper))
                    ->decrypt($encryptedData, false);
        } catch (DecryptException $e) {
            return;
        }
    }

}