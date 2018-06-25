<?php namespace PHPBook\Http;

abstract class Script {

	public static function isConsole(): Bool {

		if (substr(php_sapi_name(), 0, 3) == 'cgi') {
		    
		    return false;

		} else {
		    
		    return true;

		};

	}

}
