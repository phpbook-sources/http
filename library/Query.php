<?php namespace PHPBook\Http;

class Query {    

    private $parameter;

    private $rules;
    
    public function __construct(Parameter $parameter, Array $rules = []) {
        $this->setParameter($parameter);
        $this->setRules($rules);
    }

    public function getParameter(): Parameter {
        return $this->parameter;
    }

    public function setParameter(Parameter $parameter): Query {
        $this->parameter = $parameter;
        return $this;
    }

    public function getRules(): Array {
        return $this->rules;
    }

    public function setRules(Array $rules): Query {
        $this->rules = $rules;
        return $this;
    }
    
    public function intercept($values) {
		return $this->getParameter()->intercept($this->getRules(), $values);
    }

    public function standard($values) {
        return $this->getParameter()->standard($this->getRules(), $values);
    }

    public function schema() {
        return $this->getParameter()->schema($this->getRules());
    }

}
