<?php namespace PHPBook\Http\Configuration;

abstract class Meta {
    
    private static $name;

    private static $version;

    private static $email;

    private static $phone;

    public static function setName(String $name) {
        Static::$name = $name;
    }

    public static function getName(): ?String {
        return Static::$name;
    }

    public static function setVersion(String $version) {
        Static::$version = $version;
    }

    public static function getVersion(): ?String {
        return Static::$version;
    }

    public static function setEmail(String $email) {
        Static::$email = $email;
    }

    public static function getEmail(): ?String {
        return Static::$email;
    }

    public static function setPhone(String $phone) {
        Static::$phone = $phone;
    }

    public static function getPhone(): ?String {
        return Static::$phone;
    }

}
