<?php namespace PHPBook\Http\Configuration;

abstract class Directory {
    
    private static $docs = 'docs';

    private static $app = 'app';

    private static $default = 'docs';

    public static function setDocs(String $docs) {
        Static::$docs = $docs;
    }

    public static function getDocs(): String {
        return Static::$docs;
    }

    public static function setApp(String $app) {
        Static::$app = $app;
    }

    public static function getApp(): String {
        return Static::$app;
    }

    public static function setDefault(String $default) {
        Static::$default = $default;
    }

    public static function getDefault(): String {
        return Static::$default;
    }

}
