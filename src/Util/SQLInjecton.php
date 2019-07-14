<?php

	namespace src\Util {
		class SQLInjection {
			public static function clearString($string){
				if(!is_array($string)){
					$cmd = array('SELECT', 'UPDATE', 'DELETE', 'INSERT', 'CREATE', 'DROP', 'TRUNCATE', 'DATABASES', 'ALTER', 'REPLACE', 'OR', 'AND');
					$pattern = array();
					$pattern[] = '/<script.*>.*<\/script>/';
					$pattern[] = '/<(\/)?script>/';
					$pattern[] = '/^((\s*|;*)(('.implode(')|(', $cmd).')))\s+/i';//inicio
					$pattern[] = '/((\s+)|(;+\s*))(('.implode(')|(', $cmd).'))\s+/i';//meio
					$pattern[] = '/((\s+)|(;+\s*))(('.implode(')|(', $cmd).'))$/i';//fim
					$string = preg_replace($pattern, '', $string);
					$string = trim($string);
				}
				return $string;
			}
		}
	}