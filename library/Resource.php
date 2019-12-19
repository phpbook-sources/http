<?php namespace PHPBook\Http;

class Resource {
    
	private $categoryCode;

    private $middlewareCode;

    private $uri;

    private $notes;

    private $type;
    
    private $inputUri;

    private $inputQuery;

    private $inputBody;

    private $controller;

    private $output;

    private $isBufferOutput;

    private $cacheHours;
	
	public function setCategoryCode(String $categoryCode): Resource {
        $this->categoryCode = $categoryCode;
        return $this;
    }

    public function getCategoryCode(): ?String {
        return $this->categoryCode;
    }

    public function setMiddlewareCode(String $middlewareCode): Resource {
        $this->middlewareCode = $middlewareCode;
        return $this;
    }

    public function getMiddlewareCode(): ?String {
        return $this->middlewareCode;
    }

    public function setUri(String $uri): Resource {
        $this->uri = $uri;
        return $this;
    }

    public function getUri(): String {
        return $this->uri;
    }

    public function setNotes(String $notes): Resource {
        $this->notes = $notes;
        return $this;
    }

    public function getNotes(): ?String {
        return $this->notes;
    }

    public function setType(String $type): Resource {
        $this->type = $type;
        return $this;
    }

    public function getType(): String {
        return $this->type;
    }

    public function setInputUri(String $type, String $element, Array $rules): Resource {
        $this->inputUri = [$type, $element, $rules];
        return $this;
    }

    public function getInputUri(): ?Array {
        return $this->inputUri;
    }

    public function setInputQuery(String $type, String $element, Array $rules): Resource {
        $this->inputQuery = [$type, $element, $rules];
        return $this;
    }

    public function getInputQuery(): ?Array {
        return $this->inputQuery;
    }

    public function setInputBody(String $type, String $element, Array $rules): Resource {
        $this->inputBody = [$type, $element, $rules];
        return $this;
    }

    public function getInputBody(): ?Array {
        return $this->inputBody;
    }

    public function setController(String $controller, String $method): Resource {
        $this->controller = [$controller, $method];
        return $this;
    }

    public function getController(): Array {
        return $this->controller;
    }

    public function setOutput(String $type, String $element, Array $rules): Resource {
        $this->output = [$type, $element, $rules];
        return $this;
    }

    public function getOutput(): ?Array {
        return $this->output;
    }

    public function setIsBufferOutput(Bool $isBufferOutput): Resource {
        $this->isBufferOutput = $isBufferOutput;
        return $this;
    }

    public function getIsBufferOutput(): ?Bool {
        return $this->isBufferOutput;
    }

    public function setCacheHours(Int $cacheHours): Resource {
        $this->cacheHours = $cacheHours;
        return $this;
    }

    public function getCacheHours(): ?Int {
        return $this->cacheHours;
    }

}