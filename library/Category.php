<?php namespace PHPBook\Http;

class Category {
    
    private $code;

    private $name;

    public function setCode(String $code): Category {
        $this->code = $code;
        return $this;
    }

    public function getCode(): String {
        return $this->code;
    }

    public function setName(String $name): Category {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?String {
        return $this->name;
    }
	
}