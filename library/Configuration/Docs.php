<?php namespace PHPBook\Http\Configuration;

abstract class Docs {
    
    private static $primaryColor = '#5e96c5';

    private static $secondaryColor = '#28436b';

    public static function setPrimaryColor(String $primaryColor) {
        Static::$primaryColor = $primaryColor;
    }

    public static function getPrimaryColor(): ?String {
        return Static::$primaryColor;
    }

    public static function setSecondaryColor(String $secondaryColor) {
        Static::$secondaryColor = $secondaryColor;
    }

    public static function getSecondaryColor(): ?String {
        return Static::$secondaryColor;
    }

}
