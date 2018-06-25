<?php namespace PHPBook\Http;

abstract class Script {

	public static function isConsole(): Bool {

		if (PHP_SAPI === 'cli') {

			return true;

		} else {

			return false;

		}

	}

}
