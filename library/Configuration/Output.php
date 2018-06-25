<?php namespace PHPBook\Http\Configuration;

abstract class Output {
    
    private static $type = 'json';

    private static $exception = ['type' => 'exception', 'message' => '@'];

    private static $content = ['type' => 'success', 'content' => '@'];

    public static function setType(String $type) {
        Static::$type = $type;
    }

    public static function getType(): String {
        return Static::$type;
    }
    
    public static function setException(Array $exception) {
        Static::$exception = $exception;
    }

    public static function getException(): Array {
        return Static::$exception;
    }

    public static function setContent(Array $content) {
        Static::$content = $content;
    }

    public static function getContent(): Array {
        return Static::$content;
    }

}
