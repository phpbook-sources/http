<?php namespace PHPBook\Http\Parameter;

class One extends \PHPBook\Http\Parameter {    

    private $element;

    public function __construct(String $elementClass, String $description) {
        $this->setElementClass($elementClass);
        $this->setDescription($description);
    }

    public function getElementClass(): String {
        return $this->elementClass;
    }

    public function setElementClass(String $elementClass): One {
        $this->elementClass = $elementClass;
        return $this;
    }

    public function intercept(Array $rules, $value) {
		$item = new \StdClass;
		$elementClass = $this->getElementClass();
		foreach((new $elementClass)->getParameters() as $name => $parameter) {	
			if ($this->hasInRule($rules, $name)) {
				$parser = ((is_object($value)) and (property_exists($value, $name))) ? $value->{$name} : 
                    (((is_array($value)) and (array_key_exists($name, $value))) ? $value[$name] : Null);
				if ($parser) {
					$item->{$name} = $parameter->intercept($this->nestRules($rules, $name), $parser);
				} else {
					$item->{$name} = $parser;
				};
			};
        };	
		return $item;
    }
	
	public function schema(Array $rules) {
		$schema = new \StdClass;
		$schema->description = $this->getDescription();
		$schema->type = '@OneOf';
		$schema->schema = new \StdClass;
		$elementClass = $this->getElementClass();
        foreach((new $elementClass)->getParameters() as $name => $parameter) {	
			if ($this->hasInRule($rules, $name)) {
				$schema->schema->{$name} = $parameter->schema($this->nestRules($rules, $name));
			};
        };		
        return $schema;
	}
	
}
