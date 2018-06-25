<?php namespace PHPBook\Http;

abstract class Url {
    
    public static function get(String $uri = Null): String {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/' . $uri;
    }


}
