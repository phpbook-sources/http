<?php namespace PHPBook\Http;

abstract class Element {

    private $description = 'No description';

    private $parameters = [];

    public function setDescription(String $description): Element {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): String {
        return $this->description;
    }
    
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
