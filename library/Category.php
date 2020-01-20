<?php namespace PHPBook\Http;

class Category {
    
    private $code;

    private $name;

    private $mainResourceCategoryCode;

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

    public function setMainResourceCategoryCode(String $mainResourceCategoryCode): Category {
        $this->mainResourceCategoryCode = $mainResourceCategoryCode;
        return $this;
    }

    public function getMainResourceCategoryCode(): ?String {
        return $this->mainResourceCategoryCode;
    }


}