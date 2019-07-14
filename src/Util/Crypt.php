<?php

    namespace src\Util {
        class Crypt {

            const ENCRYPTION_ALGORITHM = 'AES-256-CBC';

            const KEY_HASHING_ALGORITHM = 'sha256';

            const MINHA_SENHA = '/|#-&|@_!|';

            public function __construct() {}

            public static function encrypt($plaintext) {
                $plaintext = hash(Cryptor::KEY_HASHING_ALGORITHM, $plaintext, false);
                $key = hash(Cryptor::KEY_HASHING_ALGORITHM, MINHA_SENHA, true);
                $iv = openssl_random_pseudo_bytes(16);
                $ciphertext = openssl_encrypt($plaintext, self::ENCRYPTION_ALGORITHM, $key, OPENSSL_RAW_DATA, $iv);
                $hash = hash_hmac(self::KEY_HASHING_ALGORITHM, $ciphertext, $key, true);
                $out = $iv . $hash . $ciphertext;
                return base64_encode($out);
            }

            public static function decrypt($ivHashCiphertext) {
                $ivHashCiphertext = base64_decode($ivHashCiphertext);
                $iv = substr($ivHashCiphertext, 0, 16);
                $hash = substr($ivHashCiphertext, 16, 32);
                $ciphertext = substr($ivHashCiphertext, 48);
                $key = hash(self::KEY_HASHING_ALGORITHM, MINHA_SENHA, true);
                if (hash_hmac(self::KEY_HASHING_ALGORITHM, $ciphertext, $key, true) !== $hash) return "senha invalida";
                return openssl_decrypt($ciphertext, Cryptor::ENCRYPTION_ALGORITHM, $key, OPENSSL_RAW_DATA, $iv);
            }
        }
    }