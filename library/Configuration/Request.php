<?php namespace PHPBook\Http\Configuration;

abstract class Request {

	private static $controllersPathRoot = null;

	private static $proxiesPathRoot = null;

	private static $proxiesNamespace  = null;

	public static function getControllersPathRoot(): ?String {
		return Static::$controllersPathRoot;
	}

	public static function setControllersPathRoot(String $controllersPathRoot) {
		Static::$controllersPathRoot = $controllersPathRoot;
	}

	public static function getProxiesPathRoot(): ?String {
		return Static::$proxiesPathRoot;
	}

	public static function setProxiesPathRoot(String $proxiesPathRoot) {
		Static::$proxiesPathRoot = $proxiesPathRoot;
	}

	public static function getProxiesNamespace(): ?String {
		return Static::$proxiesNamespace;
	}

	public static function setProxiesNamespace(String $proxiesNamespace) {
		Static::$proxiesNamespace = $proxiesNamespace;
	}

}