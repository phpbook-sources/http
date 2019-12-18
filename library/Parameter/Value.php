<?php namespace PHPBook\Http\Parameter;

class Value extends \PHPBook\Http\Parameter {    

    public function __construct(String $description, ?String $method = null) {
        $this->setDescription($description);
        $this->setMethod($method);
    }

    public function empty() {
        return null;
    }

    public function intercept(Array $rules, $value) {
        return trim($value);
    }

    public function standard(Array $rules, $value) {
        return trim($value);
    }

	public function schema(Array $rules) {
		return $this->getDescription();
	}
	
}
