<?php

class Security {

    const KEY = 'SpA!nic4';

    private static function encryptString($value) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(self::KEY), $value, MCRYPT_MODE_CBC, md5(md5(self::KEY))));
    }

    private static function decryptString($value) {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(self::KEY), base64_decode($value), MCRYPT_MODE_CBC, md5(md5(self::KEY))), "\0");
    }

    public static function encrypt($object) {
        $serializedObject = serialize($object);
        return self::encryptString($serializedObject);
    }

    public static function decrypt($encryption) {
        $serializedObject = self::decryptString($encryption);
        return unserialize($serializedObject);
    }

    public static function obtainHash($value, $salt = '') {
        return sha1(self::KEY.$value.$salt);
    }

    public static function generateToken() {
        return md5(uniqid(rand().self::KEY, true));
    }

    public static function generateString($length) {
        $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $out = "";
        for($i=0;$i<$length;$i++)
            $out .= substr($caracteres,rand(0,strlen($caracteres)),1); //Extraemos 1 caracter de los caracteres
        return $out;
    }
}
