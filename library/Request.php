<?php namespace PHPBook\Http;

abstract class Request {
    
	private static $category = [];
	
    private static $resources = [];

    public static function setResource(Resource $register) {
        Static::$resources[] = $register;
    }

    public static function getResources() {
        return Static::$resources;
    }
	
	public static function setCategory(Category $register) {
        Static::$category[] = $register;
    }

    public static function getCategories() {
        return Static::$category;
    }
	

}
