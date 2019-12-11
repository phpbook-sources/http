<?php namespace PHPBook\Http;

use PHPBook\Http\Parameter\One;

abstract class Parameter {

    private $description;

    private $method;
    
    public function getDescription(): String {
        return $this->description;
    }

    public function setDescription(String $description): Parameter {
        $this->description = $description;
        return $this;
    }

    public function getMethod(): ?String {
        return $this->method;
    }

    public function setMethod(?String $method): Parameter {
        $this->method = $method;
        return $this;
    }

    public abstract function intercept(Array $rules, $value);
	
	public abstract function schema(Array $rules);
	
	public function nestRules(Array $rules, String $name): Array {
		return array_filter(array_map(function($type) use($rules, $name) {
			if (array_key_exists($type, $rules)) {
				return array_filter(array_map(function($rule) use ($name) {
					$nest = explode('.', $rule);
					if (array_shift($nest) == $name) {
						return implode('.', $nest);
					};
					return false;
				}, $rules[$type]));
			} else {
				return [];
			};
		}, ['only' => 'only', 'except' => 'except']));
	}
	
	public function hasInRule(Array $rules, String $name): Bool {
		if (!((array_key_exists('only', $rules)) and (!in_array($name, $rules['only']))) and 
			!((array_key_exists('except', $rules)) and (in_array($name, $rules['except'])))) {
			return true;	
		}
		return false;
	}

}
