<?php namespace PHPBook\Http\Parameter;

class One extends \PHPBook\Http\Parameter {    

    private $elementClass;

    public function __construct(String $elementClass, String $description, ?String $method = null) {
        $this->setElementClass($elementClass);
        $this->setDescription($description);
        $this->setMethod($method);
    }

    public function getElementClass(): String {
        return $this->elementClass;
    }

    public function setElementClass(String $elementClass): One {
        $this->elementClass = $elementClass;
        return $this;
    }

    public function standard(Array $rules, $value) {
        $item = new \StdClass;
        $elementClass = $this->getElementClass();
        foreach((new $elementClass)->getParameters() as $name => $parameter) {
            if ($this->hasInRule($rules, $name)) {
                if (is_object($value)) {
                    if ($parameter->getMethod()) {
                        $parser = $value->{$parameter->getMethod()}();
                    } else {
                        $parser = $value->{$name};
                    };
                } else {
                    $parser = null;
                };
                if ($parser !== null) {
                    $item->{$name} = $parameter->standard($this->nestRules($rules, $name), $parser);
                } else {
                    $item->{$name} = $parser;
                };
            };
        };
        return $item;
    }

    public function empty() {
        return null;
    }

    public function intercept(Array $rules, $value) {
		$item = new \StdClass;
		$elementClass = $this->getElementClass();
		foreach((new $elementClass)->getParameters() as $name => $parameter) {	
			if ($this->hasInRule($rules, $name)) {
				$parser = ((is_object($value)) and (isset($value->{$name}))) ? $value->{$name} :
                    (((is_array($value)) and (array_key_exists($name, $value))) ? $value[$name] : Null);
				if ($parser !== Null) {
					$item->{$name} = $parameter->intercept($this->nestRules($rules, $name), $parser);
				} else {
					$item->{$name} = $parameter->empty();
				};
			};
        };	
		return $item;
    }
	
	public function schema(Array $rules) {
        $elementClass = $this->getElementClass();
        $elementItem = new $elementClass;
		$schema = new \StdClass;
		$schema->description = $elementItem->getDescription();
		$schema->type = '@OneOf';
		$schema->schema = new \StdClass;
        foreach($elementItem->getParameters() as $name => $parameter) {
			if ($this->hasInRule($rules, $name)) {
				$schema->schema->{$name} = $parameter->schema($this->nestRules($rules, $name));
			};
        };		
        return $schema;
	}
	
    public function example(Array $rules) {
        $elementClass = $this->getElementClass();
        $elementItem = new $elementClass;
        $example = new \StdClass;
        foreach($elementItem->getParameters() as $name => $parameter) {
            if ($this->hasInRule($rules, $name)) {
                $example->{$name} = $parameter->example($this->nestRules($rules, $name));
            };
        };
        return $example;
    }
    
}
