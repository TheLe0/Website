<?php
  
    namespace src\Util {
        class Debug {
            public static function die_dump($string) {
                var_dump($string);
                die();
            }
            public static function debug_mode_on() {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            }
            public static function debug_mode_off() {
                error_reporting(0);
            }
        }
    }