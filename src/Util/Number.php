<?php

    namespace src\Util {
        class Decimal
        {
            public static function formatToDb($valor)
            {
                $valor = str_replace('.', '', $valor);
                $valor = str_replace(',', '.', $valor);
                return $valor;
            }
            public static function formatToUse($valor, $casas_decimais = 2)
            {
                return number_format($valor, $casas_decimais, ',', '.');
            }
        }
    }
    