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
        return is_string($value) ? trim($value) : $value;
    }

    public function standard(Array $rules, $value) {
        if (is_string($value)) {
            return trim($value);
        }
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
    }

    public function schema(Array $rules) {
        return $this->getDescription();
    }
    
}
