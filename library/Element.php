<?php namespace PHPBook\Http;

abstract class Element {
    
    private $parameters = [];
    
    public function setParameter(String $name, Parameter $parameter): Element {
        $this->parameters[$name] = $parameter;
        return $this;
    }

    public function getParameter(String $name): Parameter {
        return $this->parameters[$name];
    }

    public function getParameters(): Array {
        return $this->parameters;
    }

}
