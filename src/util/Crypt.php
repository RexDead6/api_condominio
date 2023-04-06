<?php

class Crypt{
    const KEY = 'HASH_KEY_CONDOMINIO_PROJECT';

    public static function hash($password){
        return hash('sha512', self::KEY . $password);
    }

    public static function verify($password, $hash){
        return ($hash == self::hash($password));
    }
}
?>