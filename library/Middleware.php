<?php namespace PHPBook\Http;

class Middleware {

    private $code;

    private $name;

    private $inputHeader;

    private $parameters;

    private $middleware;

    private $relation;

    public function setCode(String $code): Middleware {
        $this->code = $code;
        return $this;
    }

    public function getCode(): String {
        return $this->code;
    }

    public function setName(String $name): Middleware {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?String {
        return $this->name;
    }

    public function setInputHeader(String $type, String $element, Array $rules): Middleware {
        $this->inputHeader = [$type, $element, $rules];
        return $this;
    }

    public function getInputHeader(): ?Array {
        return $this->inputHeader;
    }

    public function setParameters(array $parameters): Middleware {
        $this->parameters = $parameters;
        return $this;
    }

    public function getParameters(): ?array {
        return $this->parameters;
    }

    public function setMiddleware(String $middleware): Middleware {
        $this->middleware = $middleware;
        return $this;
    }

    public function getMiddleware(): ?String {
        return $this->middleware;
    }

    public function setRelation(?array $relation): Middleware {
        $this->relation = $relation;
        return $this;
    }

    public function getRelation(): ?array {
        return $this->relation;
    }


}