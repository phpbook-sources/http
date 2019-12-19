<?php namespace PHPBook\Http;

abstract class Request {
    
    private static $middleware = [];

	private static $category = [];
	
    private static $resources = [];

     public static function setMiddleware(Middleware $register) {
        Static::$middleware[] = $register;
    }

    public static function getMiddleware() {
        return Static::$middleware;
    }

    public static function getMiddlewareSchema($codeMiddleware) {

       foreach(self::getMiddleware() as $middlewareRequest) {
            if ($middlewareRequest->getCode() == $codeMiddleware) {
                return $middlewareRequest;
            }
        }

        return null;

    }

    public static function getMiddlewareInterceptFactory($codeMiddleware) {

       foreach(self::getMiddleware() as $middlewareRequest) {
            if ($middlewareRequest->getCode() == $codeMiddleware) {
                $middlewareClass = $middlewareRequest->getMiddleware();
                return new $middlewareClass;
            }
        }

        return null;

    }

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
