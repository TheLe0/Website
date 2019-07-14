<?php

    namespace src\Util {
        class Redirecionamento {

            public static function redirect($url)
            {
                header('Location: ' . $url);
                exit();
            }
            
        }
    }