<?php namespace PHPBook\Http\Parameter;

class Many extends \PHPBook\Http\Parameter {    

    private $elementClass;

    public function __construct(String $elementClass, String $description, ?String $method = null) {
        $this->setElementClass($elementClass);
        $this->setDescription($description);
        $this->setMethod($method);
    }

    public function getElementClass(): String {
        return $this->elementClass;
    }

    public function setElementClass(String $elementClass): Many {
        $this->elementClass = $elementClass;
        return $this;
    }

    public function standard(Array $rules, $value) {
        $items = [];
        foreach($value as $item) {
            $items[] = (new One($this->getElementClass(), 'Item'))->standard($rules, $item);
        };
        return $items;
    }

    public function empty() {
        return [];
    }

    public function intercept(Array $rules, $value) {
        $items = [];
        if (is_array($value)) {
            foreach($value as $item) {
                $items[] = (new One($this->getElementClass(), 'Item'))->intercept($rules, $item);
            };
        };
        return $items;
    }

	public function schema(Array $rules) {
        $elementClass = $this->getElementClass();
        $elementItem = new $elementClass;
		$schema = new \StdClass;
		$schema->description = $elementItem->getDescription();
		$schema->type = '@ManyOf';
		$schema->schema = new \StdClass;
        foreach($elementItem->getParameters() as $name => $parameter) {
			if ($this->hasInRule($rules, $name)) {
				$schema->schema->{$name} = $parameter->schema($this->nestRules($rules, $name));
			};
        };		
        return $schema;
	}
	
}
