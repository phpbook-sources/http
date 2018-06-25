<?php namespace PHPBook\Http\Parameter;

class Value extends \PHPBook\Http\Parameter {    

    public function __construct(String $description) {
        $this->setDescription($description);
    }
    
    public function intercept(Array $rules, $value) {
        return trim($value);
    }

	public function schema(Array $rules) {
		return $this->getDescription();
	}
	
}
