<?php

	namespace src\Util {
		class HTTP {

			public static function getUrlVar($nome){
				if (isset($_GET[$nome]) && is_array($_GET[$nome])) {
					$valor = SQLInjection::clearString($_GET[$nome]);
				} else if(isset($_GET[$nome])) {
					$valor = urldecode(Ambiente::clearString($_GET[$nome]));
				} else {
					$valor = false;
				}
				return $valor;
			}
				
			public static function getPostVar($nome){
				if(!is_array($_POST[$nome])){
					if(isset($_POST[$nome]) && $_POST[$nome] != ''){
						$valor = SQLInjection::clearString($_POST[$nome]);
					} else {
						$valor = false;
					}
					return $valor;
				} else {
					return $_POST[$nome];
				}
			}
		}
	}
	